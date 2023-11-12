create user "dbcreateur"@"localhost" identified by "dbcreateur"; 
grant alter, drop, create, references on *.* to "gututui"@"localhost";

create user "gututui"@"localhost" identified by "gututui"; 
grant select, insert, update, delete on clicommvc.* to "gututui"@"localhost";

select * from utilisateur;
