-- Database Migration for Mecânico Virtual

CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS diagnostics (
    id SERIAL PRIMARY KEY,
    user_id INTEGEr NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    vehicle_info JSONB, -- Store car model, year, etc.
    symptoms TEXT,
    result_json JSONB, -- Store AI diagnostic results (the 3 diagnostics with %)
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);
