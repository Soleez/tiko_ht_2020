CREATE TABLE IF NOT EXISTS contractor (
    contractor_id SERIAL,
    contractor_name VARCHAR(64) NOT NULL,
    PRIMARY KEY (contractor_id) 
);

-- ID:tä voidaan kysyä näillä kahdella funktiolla
-- SELECT currval(pg_get_serial_sequence('contractor', 'contractor_id'));

INSERT INTO contractor 
VALUES 
    (DEFAULT, 'Tmi Seppo Tärsky')
;

-- serial
-- https://www.postgresqltutorial.com/postgresql-serial/
-- vaatii defaultin käyttöä, jos asettaa käsin niin 
-- ID:n myöhempi rivi tulee asettumaan tämän päälle, eikä lisäys onnistu


-- Identity
-- https://www.postgresqltutorial.com/postgresql-identity-column/
-- Tämä voisi olla parempi ratkaisu, mutta en saa toimimaan yliopiston koneella ollenkaan
-- voi johtua jostakin vanhasta psql versiosta tai muusta, mutta ei välttämättä ajankäytöllisesti
-- järkevää selvittää

-- DROP TABLE contractor;
