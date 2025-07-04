USE filipinorecipes;

-- Existing recipes table
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    prep_time VARCHAR(50),
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image VARCHAR(255)
);

SELECT * FROM recipes;

-- Updated admin_users table with additional attributes
CREATE TABLE admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL, -- for hashed passwords
  full_name VARCHAR(100),
  email VARCHAR(100),
  role VARCHAR(50) DEFAULT 'admin',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

SELECT * FROM admin_users;

-- New table: reviews
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  recipe_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  rating INT CHECK (rating >= 1 AND rating <= 5),
  comment TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);

-- New table: chats (for chatbot)
CREATE TABLE chats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(100), -- to track anonymous user sessions
  message TEXT NOT NULL,
  sender ENUM('user', 'bot') NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
