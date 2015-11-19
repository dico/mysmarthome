
-- BACKUP THE OLD LOG DATA, TO PREVENT LOSSES
-- IF YOU ARE SURE STUFF's IS WORKING CORRECT, THIS CAN BE COMMENTED OUT

-- CREATE TABLE msh_devices_log_backup LIKE msh_devices_log; 
-- INSERT msh_devices_log_backup SELECT * FROM msh_devices_log;


-- RESTORE ORIGINAL DATA FROM BACKUP AND DELETE TMP STUFF
-- DROP TABLE msh_devices_log;
-- CREATE TABLE IF NOT EXISTS msh_devices_log LIKE msh_devices_log_backup;
-- INSERT msh_devices_log SELECT * FROM msh_devices_log_backup;
-- DROP TABLE msh_devices_log_tmp;






-- CREATE A NEW TABLE TO PUT THE REDUCED VALUES IN
CREATE TABLE IF NOT EXISTS msh_devices_log_tmp LIKE msh_devices_log;



-- INSERT VALUES TO msh_devices_log_tmp, GROUPED BY HOUR, THAT ARE OLDER THAN ONE YEAR
INSERT INTO
    msh_devices_log_tmp
SELECT *
FROM
  msh_devices_log AS LOG
WHERE
  FROM_UNIXTIME(LOG.time) < DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
GROUP BY
  DATE_ADD(DATE(FROM_UNIXTIME(LOG.time)), INTERVAL HOUR(FROM_UNIXTIME(LOG.time)) HOUR),
  LOG.device_int_id,
  LOG.unit_id




-- DELETE RECORDS OLDER THAN ONE YEAR FROM msh_devices_log
DELETE FROM msh_devices_log WHERE msh_devices_log.time < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 YEAR));


-- INSERT DATA FROM TMP TABLE TO msh_devices_log
INSERT msh_devices_log SELECT * FROM msh_devices_log_tmp;


-- DROP THE TMP TABLE, msh_devices_log_tmp
DROP TABLE msh_devices_log_tmp;