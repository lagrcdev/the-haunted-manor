CREATE TABLE utilisateur (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  role ENUM('client', 'admin') NOT NULL DEFAULT 'client',
  date_consentement DATETIME NOT NULL,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_utilisateur_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE chambre (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  fantome VARCHAR(100) NOT NULL,
  description TEXT NOT NULL,
  niveau_frayeur TINYINT UNSIGNED NOT NULL,
  prix_nuit DECIMAL(8, 2) NOT NULL,
  photo VARCHAR(255) NULL,
  PRIMARY KEY (id),
  CONSTRAINT ck_chambre_frayeur CHECK (niveau_frayeur BETWEEN 1 AND 5),
  CONSTRAINT ck_chambre_prix CHECK (prix_nuit > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservation (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  utilisateur_id INT UNSIGNED NOT NULL,
  chambre_id INT UNSIGNED NOT NULL,
  date_arrivee DATE NOT NULL,
  date_depart DATE NOT NULL,
  prix_total DECIMAL(10, 2) NOT NULL,
  statut ENUM('confirmee', 'annulee') NOT NULL DEFAULT 'confirmee',
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_reservation_chambre_dates (chambre_id, date_arrivee, date_depart),
  CONSTRAINT fk_reservation_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id),
  CONSTRAINT fk_reservation_chambre FOREIGN KEY (chambre_id) REFERENCES chambre (id),
  CONSTRAINT ck_reservation_dates CHECK (date_depart > date_arrivee)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
