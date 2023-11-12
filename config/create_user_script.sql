
CREATE USER 'dbcreator'@'localhost' IDENTIFIED BY 'dbcreator';
commit;
GRANT create, references, drop, alter ON *.* TO 'dbcreator'@'localhost';

CREATE USER 'gututuimvc'@'localhost' IDENTIFIED BY 'gututuimvc';
commit;
GRANT select, insert, update, delete ON clicommvc.* TO 'gututuimvc'@'localhost';