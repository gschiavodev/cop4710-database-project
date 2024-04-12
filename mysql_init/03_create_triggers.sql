DELIMITER //
CREATE TRIGGER check_event_overlap
BEFORE INSERT ON college_events.event
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1
        FROM college_events.event
        WHERE location_id = NEW.location_id
        AND date = NEW.date
        AND ((start_time < NEW.end_time) AND (end_time > NEW.start_time))
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Cannot insert overlapping event';
    END IF;
END;
//
DELIMITER ;