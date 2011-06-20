CREATE VIEW as hierarchy_managements
SELECT
 m.id,
 m.sponsor_member_id,
 m.member_id,
 m.created
FROM 
 members as m
WHERE
 m.sponsor_member_id != "" AND m.member_id != ""  