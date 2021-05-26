ALTER TABLE `tbltasks` 
MODIFY COLUMN `startdate` datetime(0) NOT NULL,
MODIFY COLUMN `duedate` datetime(0) NULL DEFAULT NULL;