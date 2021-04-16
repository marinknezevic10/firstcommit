# c:\xampp\mysql\bin\mysql -uedunova -pedunova < C:\PP22\mojaapp\zavrsni.sql
drop database if exists leta_zavrsni;
create database leta_zavrsni character set utf8mb4 COLLATE utf8mb4_croatian_ci;
use leta_zavrsni;

alter database leta_zavrsni default character set utf8mb4;

create table operater(
    sifra int not null primary key auto_increment,
    email varchar(50) not null,
    lozinka char(60) not null,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    uloga varchar(10) not null

);

insert into operater values(null,'edunova@edunova.hr',
'$2y$10$zrZRPiSX/1a6TLKA9O2jquM5xDKK3zE2o0o5U71Ages44KzVBS.IC',
'Administrator','Edunova','admin');

insert into operater values(null,'oper@edunova.hr',
'$2y$10$SYoaypJIb7bl4Z03NCAJ.enqSlBSIWKcMkoP2pdtn4YSfxqWzLVQS',
'Operater','Edunova','oper');

insert into operater values(null,'marin@gmail.com',
'$2y$12$ZItzi/V3PUiDnwj4Sc1DTu25jtaVk66oByNgpCf.fD8uiUSGxeFMu ',
'Administrator','Edunova','admin');

insert into operater values(null,'marina@gmail.com',
'$2y$12$myNWIXqW7A8D8dnO0fCzDeuYc13w/7OKGtoYDe3/YqJ7kXL4Tlili ',
'Operater','Edunova','oper');

create table igrac(
	sifra int not null primary key auto_increment,
	ime varchar(59) not null,
	prezime varchar(59) not null,
	mjestorodenja varchar(70) ,
	klub int not null,
	nacionalnost varchar(50),
	pozicija varchar(20),
	datumrodenja datetime,
	statistika int not null

);

create table statistika(
	sifra int not null primary key auto_increment,
	nastupi int not null,
	odigranominuta int,
	golovi int not null,
	asistencije int not null,
	zutikartoni int,
	crvenikartoni int

);

create table klub(
	sifra int not null primary key auto_increment,
	naziv varchar(50)not null,
	brojigracauklubu int,
	prosjekgodina decimal(3,1),
	zanimljivosti int not null,
	trener int not null
	
	
);

create table zanimljivosti(
	sifra int not null primary key auto_increment,
	osnivanje datetime,
	nazivstadiona varchar(90),
	kapacitet decimal(6,3)
	
);



create table trener(
	sifra int not null primary key auto_increment,
	ime varchar(59)not null,
	prezime varchar(59)not null,
	prethodniklub varchar(50) not null,
	datumrodenja datetime,
	mjestorodenja varchar(50),
	nacionalnost varchar(50) not null
	
);





alter table klub add foreign key(trener) references trener(sifra);
alter table igrac add foreign key(klub) references klub(sifra);
alter table igrac add foreign key(statistika) references statistika(sifra);


alter table klub add foreign key(zanimljivosti) references zanimljivosti(sifra);






