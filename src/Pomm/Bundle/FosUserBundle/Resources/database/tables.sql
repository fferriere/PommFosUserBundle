CREATE TABLE fos_user (
    id SERIAL PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    username_canonical VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    email_canonical VARCHAR(255) NOT NULL UNIQUE,
    enabled BOOLEAN NOT NULL DEFAULT FALSE,
    salt VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    last_login TIMESTAMP WITHOUT TIME ZONE,
    locked BOOLEAN NOT NULL DEFAULT FALSE,
    expired BOOLEAN NOT NULL DEFAULT FALSE,
    expires_at TIMESTAMP WITHOUT TIME ZONE,
    confirmation_token VARCHAR(255),
    password_requested_at TIMESTAMP WITHOUT TIME ZONE,
    roles TEXT NOT NULL,
    credentials_expired BOOLEAN NOT NULL DEFAULT FALSE,
    credentials_expire_at TIMESTAMP WITHOUT TIME ZONE
);
