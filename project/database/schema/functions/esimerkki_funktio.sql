-- Tein tämän noihin pakollisiin tehtäviin 16.2.2020
-- Olisi varmaan pelkkä kysely riittänyt

-- Oon enemmän tehnyt MSSQL:ää niin tämän ei tullut ihan rutiinilla.
-- Koitan selittää mitä tässä tehdään:

CREATE OR REPLACE FUNCTION kaikki_suoritukset(o_id INT)  -- Luodaan uusi funktio, jota ajetaan parametrillä
RETURNS table(                                           -- Asetetaan paluuarvo, asetin tähän taulun 
    suoritetut_kurssit VARCHAR(64)                       -- luodaan taulun sarakkeet
)                                                        -- Paluu arvon ei tarvitse olla taulu, voi hyvin olla vaikka int
AS $suorita$                                             -- Aloitetaan skripti $$ voisi olla vikka tyhjä
BEGIN                                                    -- Ne voisi myös korvata '' merkeillä, skripti on kuin merkkijono
    RETURN QUERY                                         -- Haetaan aimmin luotuun tauluun arvot
    SELECT kurssi.kurssin_nimi                           -- Suoritetaan kysely
    FROM suoritus
        LEFT OUTER JOIN kurssi ON suoritus.kurssi_id = kurssi.kurssi_id
        LEFT OUTER JOIN opiskelija ON suoritus.opiskelija_id = opiskelija.opiskelija_id
    WHERE suoritus.opiskelija_id = o_id
    ORDER BY suoritus.suoritus_hetki;                    -- Lopetetaan kysely
END;                                                     -- Lopetetaan suorite
$suorita$                                                -- Lopetetaan skripti
language plpgsql;                                        -- Määritellään kieli

-- Kun tämä on luotu sitä voidaan kutsua nimeltä antamalla parametrille arvo:
--    SELECT * FROM kaikki_suoritukset(1);

-- Tämä ei välttämättä noudata parhaita käytänteitä, mutta
-- voi auttaa varmaankin pääsemään sisälle vähän

-- Proseduurin ja funktion ero: Funktio tekee "Get" Haun, Proseduuri muokkaa "Set" dataa.
-- Voisi kokeilla onnistuuko skriptin luonti ilman $$ merkkejä
