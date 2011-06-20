CREATE VIEW view_sale_reports as 
SELECT
 s.id,  
 m.member_id AS member_id,
 m.name AS child_name,
 m.sponsor_member_id,
 IF( (m.sponsor_member_id IS NULL OR m.sponsor_member_id = "") , "" ,( SELECT members.name AS name FROM members WHERE members.member_id = m.sponsor_member_id LIMIT 1) ) AS parent_name,
 (
   SELECT banks.name AS name FROM banks WHERE (banks.id = m.bank_id) LIMIT 1
 ) AS bank_name,
   m.bank_account_num AS bank_account_num,
   s.insurance_paid AS insurance_paid,
   s.total_payment AS total_payment,
   s.target_month AS target_month,
   s.payment_clear AS payment_clear,
   s.calculated AS calculated,
   s.default_period_start AS default_period_start,
   s.default_period_until AS default_period_until from (sales s left join members m on ( (m.id = s.member_id) ) )