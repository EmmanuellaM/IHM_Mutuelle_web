-- Migration: Add last_penalty_session_id to borrowing table
-- This field tracks the last session where penalty was applied
-- to avoid applying penalties multiple times

ALTER TABLE borrowing 
ADD COLUMN last_penalty_session_id INT NULL,
ADD CONSTRAINT fk_borrowing_last_penalty_session 
    FOREIGN KEY (last_penalty_session_id) 
    REFERENCES session(id) 
    ON DELETE SET NULL;

-- Add index for performance
CREATE INDEX idx_borrowing_last_penalty_session ON borrowing(last_penalty_session_id);
