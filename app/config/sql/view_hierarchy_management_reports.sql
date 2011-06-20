CREATE view view_hierarchy_management_reports AS 
SELECT 
 h.id,	
 h.sponsor_member_id,
 (
  SELECT 
   name 
  FROM 
   members 
  WHERE 
   member_id = h.sponsor_member_id
  ) 
  AS 
   sponsor_name, 
 count(h.member_id) AS downline
FROM 
 hierarchy_managements as h 
WHERE 
 sponsor_member_id 
IN
(
 SELECT 
  DISTINCT sponsor_member_id 
 FROM 
  hierarchy_managements
 ) 
 AND 
 h.sponsor_member_id != "" 
GROUP BY 
 h.sponsor_member_id;