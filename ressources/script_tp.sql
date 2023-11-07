use clicommvc;

select * from client;

create user 'marcomvc'@'localhost' identified by 'mdp';
grant select, insert, update, delete on clicommvc.* to 'marcomvc'@'localhost';