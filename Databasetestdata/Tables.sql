-- No separate tables for persons and users in this branch

-- countries 
CREATE TABLE country (
  countryname VARCHAR(45) NOT NULL,
  PRIMARY KEY (countryname)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- addresses, both customers and staff
CREATE TABLE address (
    address_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
    address VARCHAR(50) NOT NULL,
    postalcode VARCHAR(5) NOT NULL,
    municipality VARCHAR(30) NOT NULL,
    country VARCHAR(45) NOT NULL,
    province VARCHAR(40) NOT NULL,
    state VARCHAR(30),
    PRIMARY KEY(address_id),
    FOREIGN KEY (country) REFERENCES country (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- product categories
CREATE TABLE productcategory (
  name VARCHAR(50) NOT NULL,
  creation_date DATETIME NOT NULL,
  last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (nimi)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- products
CREATE TABLE product (
  product_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL,
  price DECIMAL(5,2) NOT NULL,
  description VARCHAR(2000) NOT NULL,
  creation_date DATETIME NOT NULL,
  last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  product_category VARCHAR(50),
  PRIMARY KEY (tuote_id),
  FOREIGN KEY (product_category) REFERENCES productcategory (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- orders
CREATE TABLE order (
  order_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  ordered_amount VARCHAR(50) NOT NULL,
  order_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  creation_date DATETIME NOT NULL,
  last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  product_id SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (order_id),
  FOREIGN KEY (product_id) REFERENCES product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





--  user profiles, both customers and staff
-- final profile rights are determined by staff membership status in the onhenkilokunta boolean variable
-- true boolean value signifies staff members
CREATE TABLE userprofile (
  username VARCHAR(45) NOT NULL,
  password_hash VARCHAR(100) NOT NULL,
  first_name VARCHAR(45) NOT NULL,
  surname VARCHAR(45) NOT NULL,
  phone_number VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  address_id SMALLINT UNSIGNED NOT NULL,
  active BOOLEAN NOT NULL DEFAULT TRUE,
  is_staff BOOLEAN NOT NULL DEFAULT FALSE,
  creation_date DATETIME NOT NULL,
  last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (username),
  FOREIGN KEY (address_id) REFERENCES address(address_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usertoken (

  token_id INT AUTO_INCREMENT PRIMARY KEY,
  selector VARCHAR(255) NOT NULL,
  validator_hash VARCHAR(255) NOT NULL,
  username VARCHAR(45) NOT NULL,
  expiration_date DATETIME NOT NULL,
  FOREIGN KEY (username) REFERENCES userprofile (username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





  