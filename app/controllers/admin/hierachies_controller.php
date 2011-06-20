<?php
/**
 * using user id is the key of all linking 
 **/
App::import('Sanitize');
App::import('Controller','AdminApp');
App::import('Vendor','tcpdf/tcpdf');


// Extend the TCPDF class to create custom Header and Footer
class MyPdf extends TCPDF {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
        // Page number
        $this->Cell(0, 0,"Infomaster Consulting Centre", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Ln(4);        
        $this->Cell(0, 0,"No. 143C, Jalan Susur, Off Jalan Meru, 41050 Klang, Selangor D.E.", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 15, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
  
class HierachiesController extends AdminAppController
{
 var $name = 'Hierachies';
 var $tree = array();  
 
 function beforeFilter()
 {
  parent::beforeFilter();
  $configuration = $this->getSystemCalculationDate();
  $this->calculate_recent_start_date = $configuration['default_start_date']; 
  $this->calculate_recent_until_date = $configuration['default_until_date'];
 }
 
 function admin_index()
 {
  if($this->userinfo['role'] == 2)
  {
   $this->redirect('/admin/hierachies/listing');
  }
  else
  {
   $this->redirect('/admin/hierachies/lists');
  }
  exit;
 }
 
 function admin_lists()
 {
  $conditions = array();
  if(!empty($this->data))
  {
    if(isset($this->data['ViewHierarchyManagementReport']['sponsor_member_id']))
    {
      $conditions[0] = array('ViewHierarchyManagementReport.sponsor_member_id LIKE '=> '%'.$this->data['ViewHierarchyManagementReport']['sponsor_member_id'].'%');
      $this->paginate['limit'] = 999999999999;
    }
  }
  
  $parent_lists = $this->paginate('ViewHierarchyManagementReport',$conditions);
  $this->set('parent_lists',$parent_lists);
  $this->set('countParent',$this->ViewHierarchyManagementReport->find('count',array('conditions'=>$conditions)));
 }
 
 //For agent only
 function admin_listing()
 {
  $hierachy_lists = $this->HierarchyManagement->findBySponsorMemberId($this->userinfo['user_id']);//Find agent by user id.
  $this->set('hierachy_lists',$hierachy_lists);
 }
 
 function admin_delete()
 {
 
  if(@count($this->params['form']['id']) < 1)
  { 
   $this->Session->setFlash('Unable to delete selected state , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/hierachies/'); 
  }

  if(!is_array($this->params['form']['id']))
  {
   $this->Session->setFlash('Please select member to be deleted, from the checkboxs','default',array('class'=>'undone'));
   $this->redirect('/admin/hierachies/');
  }
  
  if($this->Member->deleteAll(array('Member.id' => $this->params['form']['id'])))
  {
   $this->Session->setFlash('Member deleted successfully','default',array('class'=>'done'));
  }
  else
  {
   $this->Session->setFlash('Unable to delete selected agent , please try again','default',array('class'=>'undone'));
  }
  $this->redirect('/admin/hierachies/'); 
  exit; 
 }
 
 /**
 *@objective : This function is used to calculate the downline and also to retrieve sponsor's records base on the specific month
 *             if the month is 23rd May which is already passed 22 the countdown date , then display the commission before the countdown date...
 *@params : per parent id    
 **/
 function admin_downline($per_parent=null,$default_start_date=null,$default_until_date=null)
 {
  
  // ----------------------------------------------------------------------------------------------------------------------------
    
  if(empty($per_parent) | !is_numeric($per_parent))
  {
   $this->Session->setFlash('System unable to retrieve member\'s information , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/hierachies/lists');
  }
  
  // ----------------------------------------------------------------------------------------------------------------------------
   
  $this->HierarchyManagement->recursive = -1;
  $this->paginate = array(
   'order' => 'HierarchyManagement.created ASC'
  );
  
  // ----------------------------------------------------------------------------------------------------------------------------
  
  if(empty($default_start_date) && empty($default_until_date))
  {
   /*
   $conditions = array('calculated' => 'Y');
   $fields = array('default_period_start','default_period_until');
   $sale_date_info = $this->Sale->find('first',array('conditions'=>$conditions,'fields'=>$fields));
   $default_start_date = $sale_date_info['Sale']['default_period_start'];
   $default_until_date = $sale_date_info['Sale']['default_period_until'];
   */
   if(date("d") >= 22)
   {
    $default_start_date = date("Ymd",mktime(0,0,0,date("n"),22,date("Y")));
    $default_until_date = date("Ymd",mktime(0,0,0,(date("n")+1),21,date("Y")));
   }
   else
   {
    $default_start_date = date("Ymd",mktime(0,0,0,(date("n")-1),22,date("Y")));
    $default_until_date = date("Ymd",mktime(0,0,0,(date("n")),21,date("Y")));
   }
  }
  
  $conditions = array('sponsor_member_id' => $per_parent);
  $child_node_lists = $this->paginate('HierarchyManagement',$conditions);

  foreach($child_node_lists as $index => &$per_node)
  {
   $member_info = $this->Member->findByMemberId($per_node['HierarchyManagement']['member_id'],array('name'));
   $per_node['HierarchyManagement']['child_name']  = $member_info['Member']['name']; 
  }

  $parent_info = $this->Member->findByMemberId($per_parent,array('email','name')); 
  $parent_commission_info = $this->getCurrentCommissionEarned($per_parent,$default_start_date,$default_until_date);
  
  $this->set('per_parent',$per_parent);
  $this->set('parent_info',$parent_info);
  $this->set('parent_commission_info',$parent_commission_info);
  $this->set('child_node_lists',$child_node_lists);
  
  //------------------------------------------------------------------------------------------------------  
  //For the archives
  
  $monthly_sales = $this->Sale->find('all',array(
   'conditions' => array('calculated'=>'Y'), 
   'fields' => array('DISTINCT Sale.default_period_start,Sale.default_period_until') , 
   'order' => 'Sale.default_period_start ASC'
   )
  );
  
  $this->set('per_parent',$per_parent);
  $this->set('monthly_sales',$monthly_sales);
  
  //------------------------------------------------------------------------------------------------------
  
 }

 
 function admin_parent_report($id=null)
 {
  if(empty($id))
  { 
   $this->Session->setFlash('Unable to select parent , please try again','default',array('class'=>'undone'));
   $this->redirect('/admin/hierachies/'); 
  }
 }
 
 function admin_tree($per_parent=null)
 {
  $group_of_members = array();
  $this->parentTree($per_parent);
  $this->Member->recursive = -1;
  foreach($this->tree as $parent => $many_parent)//Get all members from tree then retrieve information for them
  {
    foreach($many_parent as $parent_index => $single_parent)
    {
      $group_of_members[] = $single_parent;
    }
  }
  
  array_push($group_of_members,$per_parent);//get the info for the parent.
  $conditions = array('Member.member_id '=> $group_of_members);
  $member_info = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>array('member_id','name')));
  $member_info2 = $this->Member->find('list',array('conditions'=>$conditions,'fields'=>array('member_id','gender')));
 
  $this->set('per_parent',$per_parent);
  $this->set('giant_tree',$this->tree);
  $this->set('member_info',$member_info);
  $this->set('member_info2',$member_info2);
 }
  
 function getDaddyBoys($parent)
 {  
  $clean_node = array();
  $childNodes = $this->HierarchyManagement->find(
  'list',
   array(
   'conditions' => array('HierarchyManagement.sponsor_member_id' => $parent) , 
   'fields' => array('HierarchyManagement.member_id'), 
   'ORDER' => 'created ASC' 
   )
  );

  foreach($childNodes as $index => $per_node)
  {
    $clean_node[] = $per_node;
  }

  return $clean_node; 
 }
 
 /**
  *@objective: The function below is to calculate the commission of each individual,this function is similar in controller SALES
 **/
 function getCurrentCommissionEarned($per_parent,$default_start_date=null,$default_until_date=null)
 { 
 
  $conditions = array(
  'member_id' => $per_parent, 
  'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)), 
  'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
  );
               
  $member_commission = $this->MemberCommission->find('first',array('conditions'=>$conditions));

  if(!isset($member_commission['MemberCommission']['id']))
  {
    $member_commission['MemberCommission']['default_period_start'] = date('Y-m-d',strtotime($default_start_date)); 
    $member_commission['MemberCommission']['default_period_until'] = date('Y-m-d',strtotime($default_until_date)); 
    $member_commission['MemberCommission']['level_0'] = 0;
    $member_commission['MemberCommission']['level_1'] = 0;
    $member_commission['MemberCommission']['level_2'] = 0;
    $member_commission['MemberCommission']['level_3'] = 0;
    $member_commission['MemberCommission']['level_4'] = 0;
    $member_commission['MemberCommission']['level_5'] = 0;
    $member_commission['MemberCommission']['level_6'] = 0;
    $member_commission['MemberCommission']['miscellaneous'] = 0;
    $member_commission['MemberCommission']['remark'] = 0;
  }

  return $member_commission;
                                              
 }
 
 
 function getnodelists()
 {
  Configure::write('debug',0); 
  $this->layout = 'ajax';
  $this->autoRender = false;
  $_POST['sponsor_member_id'] = strtolower(Sanitize::escape($_POST['sponsor_member_id']));
  $list_of_childrens = $this->HierarchyManagement->find('list',array('conditions'=>array('sponsor_member_id'=>$_POST['sponsor_member_id']),'fields'=>array('member_id')));   
  foreach($list_of_childrens as $index => $children)
  {
   $pure_child[]['children'] = $children;
  }
  return json_encode($pure_child);  
 }
 
 
 function admin_view_pdf($member_id)
 { 

   //Configure::write('debug',0);
   
   if(empty($member_id))
   {
    return false;
   }
   
   $default_start_date = $this->data['Hierachy']['default_period_start'];
   $default_until_date = $this->data['Hierachy']['default_period_until'];
 
   $html            = "";
   $page_break      = 0;
   $per_record_page = 25;
   $miscellaneous   = ife( ($this->data['Hierachy']['miscellaneous'] > 0) , $this->data['Hierachy']['miscellaneous'] , null );
   $remark          = ife( ($this->data['Hierachy']['remark'] <> "") , $this->data['Hierachy']['remark'] , "" );  
   //Save the misc and remark on misc
   
   
    //Get and update
    $fields=array('id','miscellaneous','accumulated_profit','remark');
    $conditions = array('member_id'=>$member_id,
                        'DATE_FORMAT(default_period_start,"%Y%m%d") >=' => date("Ymd",strtotime($default_start_date)),
                        'DATE_FORMAT(default_period_until,"%Y%m%d") <= '=> date("Ymd",strtotime($default_until_date)));
                        
    $update_commission_info = $this->MemberCommission->find('first',array('conditions'=>$conditions,'fields'=>$fields));
    if(!empty($miscellaneous))
    {
      if($update_commission_info['MemberCommission']['accumulated_profit'] > 0)
      {
       $update_commission_info['MemberCommission']['accumulated_profit']-=$miscellaneous;
      }
      else
      {
       $update_commission_info['MemberCommission']['accumulated_profit']=$miscellaneous;
      }
      
      
      if($update_commission_info['MemberCommission']['miscellaneous'] > 0)
      {
       $update_commission_info['MemberCommission']['miscellaneous'] = $miscellaneous;
      }
      
      $update_commission_info['MemberCommission']['remark']=$remark;   
      $this->MemberCommission->save($update_commission_info,false); 
    }
    else
    {
     $miscellaneous = @$update_commission_info['MemberCommission']['miscellaneous'];
     if(strlen($remark) < 1)
     {
      $remark = $update_commission_info['MemberCommission']['remark'];
     }
    }
   
   // create new PDF document
   $pdf = new MyPdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
   
   // set document information
   $pdf->SetCreator(PDF_CREATOR);
   $pdf->SetTitle('Monthly Bonus Statement ');
   $pdf->SetSubject('Monthly Bonus Statement ');
   //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
   
   // set default header data
   $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
   
   // set header and footer fonts
   $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
   $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
   
   // set default monospaced font
   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
   
   //set margins
   $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
   $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
   $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
   
   //set auto page breaks
   $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
   
   //set image scale factor
   $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
   
   //set some language-dependent strings
   //$pdf->setLanguageArray($l);
   
   // ---------------------------------------------------------
   
   // set default font subsetting mode
   $pdf->setFontSubsetting(true);
   
   // Set font
   // dejavusans is a UTF-8 Unicode font, if you only need to
   // print standard ASCII chars, you can use core fonts like
   // helvetica or times to reduce file size.
   $pdf->SetFont('dejavusans', '', 8, '', true);
   
   //---------------------------------------------------------------------------------------------------
   
   //Search for children
   $group_of_children = $this->HierarchyManagement->find('list',
   array(
    'conditions' => array('sponsor_member_id' => $member_id), 
    'fields' => array('member_id')
    )
   );
   
   //---------------------------------------------------------------------------------------------------
   
   //count total direct profit & also get the direct profit's member_id,name,paid,target_month,etcc
   $fields = array('total_payment',
                   'insurance_paid',
                   'target_month',
                   'member_id',
                   'child_name');
                   
   $conditions = array(
   'member_id' => $group_of_children,
   'calculated' => 'Y',
   'DATE_FORMAT(target_month,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)),
   'DATE_FORMAT(target_month,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
   );
   
   $order = array('target_month' => 'ASC');
   
   $direct_profits = $this->ViewSaleReport->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>$order)); 
    
   //Get parent's personal information
   //---------------------------------------------------------------------------------------------------
   $member_info = $this->Member->find('first',array( 'conditions' => array('Member.member_id' => $member_id)));
   //---------------------------------------------------------------------------------------------------
   
   $member_commission_info = $this->MemberCommission->find('first',
     array
     (
      'conditions' => array(
                 'member_id' => $member_info['Member']['member_id'],
                 'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)),
                 'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
                 )
     )
   );
   
   $group_member_info =$this->PaidContributor->find('all',
     array
     (
      'conditions' => array(
                 'sponsor_member_id' => $member_info['Member']['member_id'],
                 'DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => date("Ymd",strtotime($default_start_date)),
                 'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
                 )
     )
   ); 
   
   if($member_commission_info['MemberCommission']['accumulated_profit'] < 1)
   {
     $member_commission_info_2 = $this->MemberCommission->find('first',
     array
     (
      'conditions' => array(
                 'member_id' => $member_info['Member']['member_id'],
                 'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => date("Ymd",strtotime($default_until_date))
                 ),
      'order' => 'default_period_until DESC'
     )
    );
    
    //Setting
    $member_commission_info['MemberCommission']['accumulated_profit'] = $member_commission_info_2['MemberCommission']['accumulated_profit'];
   }
   
   
   
   if(!isset($member_commission_info['MemberCommission']['id']))
   {
    //$this->Session->setFlash('No commission is share by this user '.$member_info['Member']['member_id'].' for the selected month','default',array('class'=>'undone'));
    //$this->redirect('/admin/hierachies/downline/'.$member_info['Member']['member_id']);
   }

   //---------------------------------------------------------------------------------------------------
   
   $hierarchy = $this->Hierarchy->find('first');

   // ----------------------------
   
   @$new_personal_bonus = (($hierarchy['Hierarchy']['level_0']/100)*$member_commission_info['MemberCommission']['level_0']);
   @$group_sales_bonus_1 = (($hierarchy['Hierarchy']['level_1']/100)*$member_commission_info['MemberCommission']['level_1']);
   @$group_sales_bonus_2 = (($hierarchy['Hierarchy']['level_2']/100)*$member_commission_info['MemberCommission']['level_2']);
   @$group_sales_bonus_3 = (($hierarchy['Hierarchy']['level_3']/100)*$member_commission_info['MemberCommission']['level_3']);
   @$group_sales_bonus_4 = (($hierarchy['Hierarchy']['level_4']/100)*$member_commission_info['MemberCommission']['level_4']);
   @$group_sales_bonus_5 = (($hierarchy['Hierarchy']['level_5']/100)*$member_commission_info['MemberCommission']['level_5']);
   @$group_sales_bonus_6 = (($hierarchy['Hierarchy']['level_6']/100)*$member_commission_info['MemberCommission']['level_6']);
   
   @$group_bonus = ($member_commission_info['MemberCommission']['level_1']+$member_commission_info['MemberCommission']['level_2']+$member_commission_info['MemberCommission']['level_3']+$member_commission_info['MemberCommission']['level_4']+$member_commission_info['MemberCommission']['level_5']+$member_commission_info['MemberCommission']['level_6']);
   $new_group_sales_bonus = ($group_sales_bonus_1 + $group_sales_bonus_2 + $group_sales_bonus_3 + $group_sales_bonus_4 + $group_sales_bonus_5 + $group_sales_bonus_6);
 
   $total_bonus = ($new_group_sales_bonus+$new_personal_bonus);
   $total_bonus_without_deduction = ($new_group_sales_bonus+$new_personal_bonus);
   $total_bonus-= $miscellaneous;
      
   $group_sales_bonus_1 = number_format($group_sales_bonus_1, 2, '.', ',');
   $group_sales_bonus_2 = number_format($group_sales_bonus_2, 2, '.', ',');
   $group_sales_bonus_3 = number_format($group_sales_bonus_3, 2, '.', ',');
   $group_sales_bonus_4 = number_format($group_sales_bonus_4, 2, '.', ',');
   $group_sales_bonus_5 = number_format($group_sales_bonus_5, 2, '.', ',');
   $group_sales_bonus_6 = number_format($group_sales_bonus_6, 2, '.', ',');
   
   @$member_commission_info['MemberCommission']['level_1'] = number_format($member_commission_info['MemberCommission']['level_1'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_2'] = number_format($member_commission_info['MemberCommission']['level_2'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_3'] = number_format($member_commission_info['MemberCommission']['level_3'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_4'] = number_format($member_commission_info['MemberCommission']['level_4'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_5'] = number_format($member_commission_info['MemberCommission']['level_5'], 2, '.', ',');
   @$member_commission_info['MemberCommission']['level_6'] = number_format($member_commission_info['MemberCommission']['level_6'], 2, '.', ',');

   $total_bonus = number_format($total_bonus, 2, '.', ',');      
   $group_bonus = number_format($group_bonus, 2, '.', ',');
   $miscellaneous = number_format($miscellaneous, 2, '.', ',');
   $new_personal_bonus = number_format($new_personal_bonus, 2, '.', ',');
   $total_bonus_without_deduction = number_format($total_bonus_without_deduction, 2, '.', ',');
   $new_group_sales_bonus = number_format($new_group_sales_bonus, 2, '.', ',');
   @$accumulated_profit = number_format($member_commission_info['MemberCommission']['accumulated_profit'], 2, '.', ',');

   // ---------------------------------------------------------------     
   
   $max_looping = ceil(sizeof($direct_profits)/$per_record_page);
   if(!isset($max_looping) | $max_looping < 1)
   {
    $max_looping = 1;
   }
   
   // ----------------------------
   
   while($page_break < $max_looping )
   {
     $pdf->AddPage();
     $html = '
     <style>#test{border-top:1px solid black;border-bottom:1px solid black;}</style>
     <table cellspacing="0" border="0" width="100%">
     <tr>
     	<td colspan="9" height="20" align="center">
       <b>
        <font face="calibri" color="#000000">Monthly Bonus Statement For '.date("Y/m",(strtotime($default_until_date))).'</font>
       </b>
      </td>
      </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <tr>
       <td>&nbsp;</td>
      </tr>
      <tr>
       <td>'.ucwords(strtolower($member_info['Member']['name'])).' - (IFM '.$member_info['Member']['member_id'].') </td>
      </tr>';
      
      
      if(isset($member_info['Member']['address']) && !empty($member_info['Member']['address']))
      {
       $address = ucwords(strtolower($member_info['Member']['address']));
       $address = nl2br($address);
       $expode_address = explode(',',$address);
       foreach($expode_address as $index => $partial_address)
       {
        $html .= '<tr><td>';
        $html .= $partial_address;
        if( (sizeof($expode_address)-1) == $index ){
         $html .= '.';
        }else{
         $html .= ',';
        }
        $html .= '</td></tr>';
       }
      }
      else
      {
       $html .= '<tr><td>';
       $html .= @ucwords(strtolower($member_info['Member']['address_1']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['address_2']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['address_3']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['postal_code'])).' '.@ucwords(strtolower($member_info['Member']['city']));
       $html .= '<br />';
       $html .= @ucwords(strtolower($member_info['Member']['state']));
       $html .= '<br />';
       $html .= '</td></tr>';
      }
      
      $html .='<tr>
       <td>&nbsp;</td>
      </tr>
     </table>
     
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
     
     <tr>
     	<td width="46.9%" colspan="4" height="20"><b>Performance Summary</b></td>
     	<td width="6.2%">&nbsp;</td>
      <td width="46.9%" colspan="4" height="20"><b>Bonus Summary</b></td>
     </tr>
     
     <tr>
      <td width="30%">Personal Sales</td>
      <td width="1%" align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right" width="13%">'.@number_format($member_commission_info['MemberCommission']['level_0'], 2, '.', ',').'</td>
      <td width="6%">&nbsp;</td>
      <td width="30%">Personal Bonus</td>
      <td width="1%" align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right" width="13%">'.$new_personal_bonus.'</td>
     </tr>
     
     <tr>
      <td>Group Sales</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$group_bonus.'</td>
      <td>&nbsp;</td>
      <td>Group Bonus</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$new_group_sales_bonus.'</td>
     </tr>
     
     <tr>
      <td>Accumulated Sales</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$accumulated_profit.'</td>
      <td>&nbsp;</td>
      <td>Misc</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$miscellaneous.'</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">Total Bonus&nbsp;</td>
      <td align="center">:</td>
      <td width="3%" align="right">RM</td>
      <td align="right">'.$total_bonus.'</td>
     </tr>
     <tr><td>&nbsp;</td></tr>
     <tr><td>&nbsp;</td></tr>
     </table>
     
     <table width="100%" cellpadding="1" cellspacing="0" border="0">
     <tr>
      <td align="center" width="20%"><b>Member ID</b></td>
      <td align="center" width="20%"><b>Name</b></td>
      <td align="center" width="20%"><b>Submission Date</b></td>
      <td align="center"><b>Sales</b></td>
      <td align="center" width="5%"><b>%</b></td>
      <td align="center"><b>Bonus</b></td>
     </tr>
     </table>
     
     <table width="100%" cellpadding="1" cellspacing="0" border="0" >
      <tr><td colspan="9">&nbsp;</td></tr>
      <tr>
       <td align="center" width="20%"><b>Personal Sales</b></td>
       <td colspan="8">&nbsp;</td>
      </tr>
      <tr><td colspan="9">&nbsp;</td></tr>';
     
     $this->contRowDisplay($html,$direct_profits,$page_break);
     
     $html .= '
     </table>
     <table width="100%" cellpadding="1" cellspacing="0" border="0">
     <tr><td colspan="7">&nbsp;</td></tr>
     <tr><td colspan="7" align="left"><b>Group Sales</b></td></tr>
     <tr><td colspan="7">&nbsp;</td></tr>
   
     <tr>
      <td width="30%" align="center">Level 1</td>
      <td width="1%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_1'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_1'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_1.'</td>
     </tr>';
     
     foreach($group_member_info as $member_index => $group_member)
     {
       if($group_member['PaidContributor']['level'] <> "level_1")
       {
        continue;
       }
       
       $fields=array('name');
       $conditions = array('member_id'=>trim($group_member['PaidContributor']['member_id']));
       $group_name_info = $this->Member->find('first',array('fields'=>$fields,'conditions'=>$conditions));
       
       $html .= '
       <tr>
        <td width="30%" align="center">'.ucwords(strtolower($group_name_info['Member']['name'])).'</td>
        <td width="1%"></td>
        <td width="3%" align="right"></td>
        <td width="13%" align="right"></td>
        <td width="29.5%" align="center">&nbsp;</td>
        <td width="5%" align="center"></td>
        <td width="5.8%" align="right"></td>
        <td width="12.6%" align="right"></td>
       </tr>';
     
     }
     
     $html .= '<tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="30%" align="center">Level 2</td>
      <td width="1%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_2'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_2'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_2.'</td>
     </tr>';
     
     foreach($group_member_info as $member_index => $group_member)
     {
       if($group_member['PaidContributor']['level'] <> "level_2")
       {
        continue;
       }
       
       $fields=array('name');
       $conditions = array('member_id'=>trim($group_member['PaidContributor']['member_id']));
       $group_name_info = $this->Member->find('first',array('fields'=>$fields,'conditions'=>$conditions));
       
       $html .= '
       <tr>
        <td width="30%" align="center">'.ucwords(strtolower($group_name_info['Member']['name'])).'</td>
        <td width="1%"></td>
        <td width="3%" align="right"></td>
        <td width="13%" align="right"></td>
        <td width="29.5%" align="center">&nbsp;</td>
        <td width="5%" align="center"></td>
        <td width="5.8%" align="right"></td>
        <td width="12.6%" align="right"></td>
       </tr>';
     
     }
     
     $html .= '<tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="30%" align="center">Level 3</td>
      <td width="1%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_3'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_3'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_3.'</td>
     </tr>';
     
     foreach($group_member_info as $member_index => $group_member)
     {
       if($group_member['PaidContributor']['level'] <> "level_3")
       {
        continue;
       }
       
       $fields=array('name');
       $conditions = array('member_id'=>trim($group_member['PaidContributor']['member_id']));
       $group_name_info = $this->Member->find('first',array('fields'=>$fields,'conditions'=>$conditions));
       
       $html .= '
       <tr>
        <td width="30%" align="center">'.ucwords(strtolower($group_name_info['Member']['name'])).'</td>
        <td width="1%"></td>
        <td width="3%" align="right"></td>
        <td width="13%" align="right"></td>
        <td width="29.5%" align="center">&nbsp;</td>
        <td width="5%" align="center"></td>
        <td width="5.8%" align="right"></td>
        <td width="12.6%" align="right"></td>
       </tr>';
     
     }
     
     $html .= '<tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="30%" align="center">Level 4</td>
      <td width="1%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_4'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_4'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_4.'</td>
     </tr>';
     
     foreach($group_member_info as $member_index => $group_member)
     {
       if($group_member['PaidContributor']['level'] <> "level_4")
       {
        continue;
       }
       
       $fields=array('name');
       $conditions = array('member_id'=>trim($group_member['PaidContributor']['member_id']));
       $group_name_info = $this->Member->find('first',array('fields'=>$fields,'conditions'=>$conditions));
       
       $html .= '
       <tr>
        <td width="30%" align="center">'.ucwords(strtolower($group_name_info['Member']['name'])).'</td>
        <td width="1%"></td>
        <td width="3%" align="right"></td>
        <td width="13%" align="right"></td>
        <td width="29.5%" align="center">&nbsp;</td>
        <td width="5%" align="center"></td>
        <td width="5.8%" align="right"></td>
        <td width="12.6%" align="right"></td>
       </tr>';
     
     }
     
     $html .= '<tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="30%" align="center">Level 5</td>
      <td width="1%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_5'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_5'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_5.'</td>
     </tr>';
     
     foreach($group_member_info as $member_index => $group_member)
     {
       if($group_member['PaidContributor']['level'] <> "level_5")
       {
        continue;
       }
       
       $fields=array('name');
       $conditions = array('member_id'=>trim($group_member['PaidContributor']['member_id']));
       $group_name_info = $this->Member->find('first',array('fields'=>$fields,'conditions'=>$conditions));
       
       $html .= '
       <tr>
        <td width="30%" align="center">'.ucwords(strtolower($group_name_info['Member']['name'])).'</td>
        <td width="1%"></td>
        <td width="3%" align="right"></td>
        <td width="13%" align="right"></td>
        <td width="29.5%" align="center">&nbsp;</td>
        <td width="5%" align="center"></td>
        <td width="5.8%" align="right"></td>
        <td width="12.6%" align="right"></td>
       </tr>';
     
     }
     
     $html .= '<tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td width="30%" align="center">Level 6</td>
      <td width="1%"></td>
      <td width="3%" align="right">RM</td>
      <td width="13%" align="right">'.$member_commission_info['MemberCommission']['level_6'].'</td>
      <td width="29.5%" align="center">&nbsp;</td>
      <td width="5%" align="center">'.$hierarchy['Hierarchy']['level_6'].'%</td>
      <td width="5.8%" align="right">RM</td>
      <td width="12.6%" align="right">'.$group_sales_bonus_6.'</td>
     </tr>';
     
     foreach($group_member_info as $member_index => $group_member)
     {
       if($group_member['PaidContributor']['level'] <> "level_6")
       {
        continue;
       }
       
       $fields=array('name');
       $conditions = array('member_id'=>trim($group_member['PaidContributor']['member_id']));
       $group_name_info = $this->Member->find('first',array('fields'=>$fields,'conditions'=>$conditions));
       
       $html .= '
       <tr>
        <td width="30%" align="center">'.ucwords(strtolower($group_name_info['Member']['name'])).'</td>
        <td width="1%"></td>
        <td width="3%" align="right"></td>
        <td width="13%" align="right"></td>
        <td width="29.5%" align="center">&nbsp;</td>
        <td width="5%" align="center"></td>
        <td width="5.8%" align="right"></td>
        <td width="12.6%" align="right"></td>
       </tr>';
     
     }
     
     $html .= '<tr><td colspan="7">&nbsp;</td></tr>
     
     <tr>
      <td align="center"></td>
      <td></td>
      <td></td>
      <td align="center"></td>
      <td align="right" colspan="2"> <b>Grand Bonus (RM)</b> </td>
      <td align="right">RM</td>
      <td align="right"><b>'.$total_bonus_without_deduction.'</b></td>
     </tr>
     <tr><td colspan="7">&nbsp;</td></tr>';

     if(isset($remark))
   	 {	
   	 $html .='
        <tr>
         <td align="center"></td>
         <td align="left" colspan="6"> *'.$remark.' </td>
         <td></td>
        </tr>';
    	}
     
     $html .= '</table>';
     $page_break+=1;

     // output the HTML content
     $pdf->writeHTML($html, true, 0, true, true);
     
   }//end while;
   
   // ---------------------------------------------------------
   // Close and output PDF document
   // This method has several options, check the source code documentation for more information.
   //echo $html;
   //exit;
   $pdf->Output($member_info['Member']['member_id'].'.pdf', 'I');
   
   //============================================================+
   // END OF FILE
   //============================================================+ 
 }
 
 /**
  * @Objective : To continously display table row..
  **/
 function contRowDisplay(&$html,$direct_profits,$page_break,$start_loop=0)
 {
 
  if(!isset($direct_profits[$start_loop]['ViewSaleReport']['total_payment']) | (($start_loop)%25) == 24 )
  {
   return $html;
  }
    
  if($page_break > 0)
  {
   if($start_loop < 1)
   {
    $start_loop = ((($page_break*25))-1);
   }
  }
  
  $html .= '
  <tr>
   <td align="center" width="20%">'.$direct_profits[$start_loop]['ViewSaleReport']['member_id'].'</td>
   <td width="20%">'.ucwords(strtolower($direct_profits[$start_loop]['ViewSaleReport']['child_name'])).'</td>
   <td align="center" width="20%">'.$direct_profits[$start_loop]['ViewSaleReport']['target_month'].'</td>
   <td width="3%">RM</td>
   <td width="13.5%" align="right">'.number_format($direct_profits[$start_loop]['ViewSaleReport']['total_payment'], 2, '.', ',').'&nbsp;</td>
   <td align="center" width="5.5%">15%</td>
   <td width="2.5%">&nbsp;</td>
   <td width="3%">RM</td>
   <td align="right" width="12.5%">'.number_format((0.15*$direct_profits[$start_loop]['ViewSaleReport']['total_payment']), 2, '.', ',').'</td>
  </tr>';
  
  return $this->contRowDisplay($html,$direct_profits,$page_break,$start_loop+=1);
  
 }
 
 function admin_export($per_parent=null,$from=null,$to=null)
 {
  
  Configure::write('debug', 0);

  $fields = array('level_0',
                  'level_1',
                  'level_2',
                  'level_3',
                  'level_4',
                  'level_5',
                  'level_6',
                  'default_start_date',
                  'default_until_date');
    
  $to = date('Ymd',strtotime($to));
  $from = date('Ymd',strtotime($from));
  $conditions = array('member_id'=>$per_parent,
                      'DATE_FORMAT(default_start_date,"%Y%m%d") >= ' => $from,
                      'DATE_FORMAT(default_start_date,"%Y%m%d") <= ' => $to);
                      
  $commissions_info = ($this->MemberCommission->find('first',array('conditions'=>$conditions,'fields'=>$fields)));
  
  $fields = array('member_id','name','address');
  $parent_info = array_shift($this->Member->find('first',array('conditions'=>array('member_id'=>$per_parent),'fields'=>$fields)));  
  
  
  //Get direct profit from the table
  $conditions = array('DATE_FORMAT(default_period_start,"%Y%m%d") >= ' => $from,'DATE_FORMAT(default_period_until,"%Y%m%d") <= ' => $to , 'sponsor_member_id'=>$per_parent);
  $direct_profits = ($this->Sale->find('all',array('conditions'=>$conditions)));
  
  //Getting childs name
  foreach($direct_profits as $index => &$per_direct_sales)
  {
   $fields = array('Member.name');
   $conditions = array('Member.member_id'=>$per_direct_sales['Sale']['member_id']);
   $member_info = $this->Member->find('first',array('conditions'=>$conditions,'fields'=>$fields));
   $per_direct_sales['Sale']['child_name'] = $member_info['Member']['name']; 
  }

 $content = '
 <table frame="void" cellspacing="0" cols="9" rules="none" border="0">
	<colgroup><col width=140><col width=23><col width=181><col width=41><col width=35><col width=82><col width=47><col width=75><col width=75></colgroup>
	<tbody>
		<tr>
			<td colspan=3 width=344 height=68 align=left valign=bottom> 
    <img height="64" width="293" src="http://ifm2u.com/img/company-logo.png" style="display: block;">
   </td>
			<td width=41 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td width=35 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td width=82 align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td width=47 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td width=75 align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td width=75 align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td colspan=9 height=20 align=center valign=bottom><b><u><font face="calibri" color="#000000">Monthly bonus statement for '.date('Y/m',strtotime($to)).'</font></u></b></td>
			</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height="20" align="left" valign="bottom" width="200">
    <font face="calibri" color="#000000">
    '.ucwords(strtolower($parent_info['name'])).' (IFM-'.$parent_info['member_id'].')
    </font>
   </td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=right valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000">'.ucwords(strtolower($parent_info['address'])).'</font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><b><u><font face="calibri" color="#000000">Performance Summary</font></u></b></td>
			<td align=right valign=bottom><b><u><font face="calibri" color="#000000"><br></font></u></b></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom width="200"><b><u><font face="calibri" color="#000000">Bonus Summary</font></u></b></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><b><u><font face="calibri" color="#000000"><br></font></u></b></td>
			<td align=left valign=bottom><b><u><font face="calibri" color="#000000"><br></font></u></b></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>';
  
  $total_group_sales = 0;
  $total_personal_sales = 0;
  $total_group_sales_bonus = 0;
  $total_personal_sales_bonus = 0;
  $hierarchy = $this->Hierarchy->find('first');
		
  foreach($direct_profits as $index => $per_sale_info)
		{
		 $total_personal_sales += $per_sale_info['Sale']['insurance_paid'];
		}
		
		$total_personal_sales_bonus = ($total_personal_sales*($hierarchy['Hierarchy']['level_0']/100));
  
  foreach($commissions_info['MemberCommission'] as $index => $group_sales)
  {
   if($index == 'level_0' | $index == 'default_start_date' | $index == 'default_until_date')
   {
    continue;
   }
   
   $total_group_sales+=$group_sales;
   $total_group_sales_bonus += ($group_sales*($hierarchy['Hierarchy'][$index]/100));   
  }
  			
	 $content .= '<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000">Personal Sales </font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000">:</font></td>
			<td colspan=2 align=left valign=bottom sdval="200"><font face="calibri" color="#000000">'.number_format($total_personal_sales,2,'.',',').'</font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000">Personal Bonus</font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align="center" valign="bottom"><font face="calibri" color="#000000">:</font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.$total_personal_sales_bonus.'</font></td>
		</tr>';
		
		$content .= '<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000">Group Sales</font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000">:</font></td>
			<td colspan=2 align=left valign=bottom sdval="0"><font face="calibri" color="#000000">'.number_format($total_group_sales,2,'.',',').'</font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000">Group Bonus</font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align="center" valign="bottom"><font face="calibri" color="#000000">:</font></td>
			<td colspan=2 align=left valign=bottom sdnum="1033;0;_(* #,##0.00_);_(* \(#,##0.00\);_(* &quot;-&quot;??_);_(@_)">
   <font face="calibri" color="#000000">'.number_format($total_group_sales_bonus,2,'.',',').'</font></td>
			</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000">Accumulated Sales</font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000">:</font></td>
			<td colspan=2 align=left valign=bottom sdval="200"><b><font face="calibri" color="#000000">'.number_format(($total_personal_sales+$total_group_sales),2,'.',',').'</font></b></td>
			<td align=left valign=bottom><font face="calibri" color="#000000">Misc</font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align="center" valign="bottom"><font face="calibri" color="#000000">:</font></td>
			<td colspan=2 align=left valign=bottom sdnum="1033;0;_(* #,##0.00_);_(* \(#,##0.00\);_(* &quot;-&quot;??_);_(@_)"><font face="calibri" color="#000000"></font></td>
			</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=21 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><b><font face="calibri" color="#000000">Total Bonus (RM)</font></b></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><b><font face="calibri" color="#000000"><br></font></b></td>
			<td align=center valign=bottom><font face="calibri" color="#000000">:</font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000">'.number_format(($total_group_sales_bonus+$total_personal_sales_bonus),2,'.',',').'</font></td>
			</tr>
		<tr>
			<td height=21 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" height=20 align=center valign=bottom><font face="calibri" color="#000000">Member ID</font></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" colspan=2 align=center valign=bottom><font face="calibri" color="#000000">Name</font></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" colspan=2 align=center valign=bottom><font face="calibri" color="#000000">Sub Date</font></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000">Sales</font></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align=center valign=bottom><font face="calibri" color="#000000">%</font></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000">Bonus</font></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0%"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><b><u><font face="calibri" color="#000000">Personal Sales</font></u></b></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom><font face="calibri" color="#000000"></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>';

		foreach($direct_profits as $index => $per_sale_info)
		{
		 
		 $content .= '<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000">IFM-'.$per_sale_info['Sale']['member_id'].'</font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000">'.ucwords(strtolower($per_sale_info['Sale']['child_name'])).'</font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdval="200" sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.$per_sale_info['Sale']['insurance_paid'].'</font></td>
			<td align=center valign=bottom sdval="0.15" sdnum="1033;1033;0%"><font face="calibri" color="#000000">'.$hierarchy['Hierarchy']['level_0'].'%</font></td>
			<td style="border-bottom: 1px solid #000000" align=center valign=bottom sdval="30" sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.($per_sale_info['Sale']['insurance_paid'] * (($hierarchy['Hierarchy']['level_0']/100)) ).'</font></td>
			<td align=center valign=bottom sdval="30" sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.number_format($per_sale_info['Sale']['insurance_paid']*(($hierarchy['Hierarchy']['level_0']/100)),2,'.',',').'</font></td>
	 	</tr>';
		}
		
		$content .= '
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0%"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><b><u><font face="calibri" color="#000000">Group Sales</font></u></b></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0%"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0%"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>';
  
  //Getting the hierachy default setting
  $this->Hierarchy->recursive = -1; 
  $hierachy_management = $this->Hierarchy->find('first');
  
  foreach($commissions_info['MemberCommission'] as $level => $commission_earned)
  { 
  
    switch($level):
      case "default_until_date":
      case "default_start_date":
      case "level_0":
      break;
      default:
      $content .= '<tr><td height=20 align=left valign=bottom><b><font face="calibri" color="#000000">'.ucfirst(str_replace('_',' ',$level)).'</font></b></td>
   			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
   			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
   			<td align=center valign=bottom sdval="0" sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.$commission_earned.'</font></td>
   			<td align=center valign=bottom sdval="0.035" sdnum="1033;0;0.0%"><font face="calibri" color="#000000">'.$hierachy_management['Hierarchy'][$level].'%</font></td>
   			<td align=center valign=bottom sdval="0" sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.number_format(($commission_earned*($hierachy_management['Hierarchy'][$level]/100)),2,'.',',').'</font></td>
   			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000">'.number_format(($commission_earned*($hierachy_management['Hierarchy'][$level]/100)),2,'.',',').'</font></td>
   		</tr>';   
    endswitch;
   
  }
  
		$content.='<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td height=21 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00" width="200"><b><font face="calibri" color="#000000">Grand Bonus (RM)</font></b></td>
			<td align=center valign=bottom sdnum="1033;1033;0%"><b><font face="calibri" color="#000000"><br></font></b></td>
			<td align=center valign=bottom sdnum="1033;1033;0.00"><b><font face="calibri" color="#000000"><br></font></b></td>
			<td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" align=center valign=bottom sdval="30" sdnum="1033;1033;0.00"><b><font face="calibri" color="#000000">'.number_format($total_group_sales_bonus,2,'.',',').'</font></b></td>
		</tr>
		<tr>
			<td height=21 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td colspan=2 align=center valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
		</tr>
		<tr>
			<td style="border-top: 1px solid #000000" colspan=9 height=31 align=center valign=bottom>
    <b>
     <font face="calibri" size=4 color="#000000">Infomaster Consulting Centre (SA0147365-K)</font>
    </b>
   </td>
			</tr>
		<tr>
			<td colspan=9 height=20 align=center valign=bottom>
    <font face="calibri" color="#000000">
     No. 143C, Jalan Susur, Off Jalan Meru, 41050 Klang, Selangor d.e.
    </font>
   </td>
			</tr>
		<tr>
			<td colspan=9 height=20 align=center valign=bottom>
    <font face="calibri" color="#000000">
     Tel : 016-690 8998 Fax : 03-3345 3996
    </font>
   </td>
			</tr>
	</tbody>
</table>';
     
  $filename = str_replace(" ","_",strtolower($parent_info['name'])).'.xls';
  
  //header("Content-type: application/vnd.ms-excel");
  //header("Content-Disposition: attachment; filename=$filename");
  //header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
  //header("Pragma: public");
  //header("Expires: 0");
  echo $content;
  exit;
  
 }
 
 
 function get_report_header()
 {
  $header = '
   <table cellspacing="0" cols="9" border="0">
  	<colgroup><col width=140><col width=23><col width=181><col width=41><col width=35><col width=82><col width=47><col width=75><col width=75></colgroup>
  	<tbody>
  		<tr>
  			<td colspan=3 width=344 height=68 align=left valign=bottom> 
      <img height="64" width="293" src="http://ifm2u.com/img/company-logo.png" style="display: block;">
     </td>
  			<td width=41 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td width=35 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td width=82 align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td width=47 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td width=75 align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td width=75 align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  		</tr>
  		<tr>
  			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  		</tr>
  		<tr>
  			<td colspan=9 height=20 align=center valign=bottom><b><u><font face="calibri" color="#000000">Monthly bonus statement for </font></u></b></td>
  			</tr>
  		<tr>
  			<td height=20 align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  			<td align=left valign=bottom sdnum="1033;1033;0.00"><font face="calibri" color="#000000"><br></font></td>
  		</tr>
    </table>';
    
    return $header;
 }
 
}

?>