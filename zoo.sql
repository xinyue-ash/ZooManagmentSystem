drop table Animal_Diet;
drop table Zone_Shortage;
drop table Take_Care_Of;
drop table Animal_BasicInfo;
drop table Manage;
drop table Location_Method;
drop table Provide_Supplies;
drop table Contact;
drop table Vets_Occupation;
drop table Contact_Info;
drop table Zones;
drop table Animal_Carers;
drop table Supply_Managers;
drop table Company;
drop table Animal_Hospitals;


CREATE TABLE Animal_Carers
	(carerID INTEGER,
	cname VARCHAR(20),
	workingLocation VARCHAR(20),
	toolSetID INTEGER,
	expertise VARCHAR(20),
	feedingSchedule VARCHAR(20),
	CHECK ((feedingSchedule IS NOT NULL) 
		OR (toolSetID IS NOT NULL) 
		OR (expertise  IS NOT NULL)),
	PRIMARY KEY (carerID));
 
grant select on Animal_Carers to public;


CREATE TABLE Zones
	(zoneName VARCHAR(20),
	numAnimals INTEGER,
	PRIMARY KEY (zoneName));

grant select on Zones to public;


CREATE TABLE Animal_BasicInfo 
	(animalID INTEGER,
	gender VARCHAR(10),
	needVet INTEGER,
	since DATE,
	zoneName VARCHAR(20),
	species VARCHAR(20),
	PRIMARY KEY (animalID),
	FOREIGN KEY (zoneName) references Zones);

grant select on Animal_BasicInfo to public;
 

CREATE TABLE Animal_Diet 
	(species VARCHAR(20),
	diet VARCHAR(100),
	PRIMARY KEY (species));

grant select on Animal_Diet to public;


CREATE TABLE Zone_Shortage 
	(zoneName VARCHAR(20),
	supplyShortage VARCHAR(20),
	PRIMARY KEY (zoneName, supplyShortage),
	FOREIGN KEY (zoneName) references Zones ON DELETE CASCADE);

grant select on Zone_Shortage to public;
 

CREATE TABLE Take_Care_Of 
	(animalID INTEGER,
	carerID INTEGER,
	since DATE,
	PRIMARY KEY (animalID, carerID),
	FOREIGN KEY (animalID) references Animal_BasicInfo,
	FOREIGN KEY (carerID) references Animal_Carers);
 
grant select on Take_Care_Of to public;
 

CREATE TABLE Supply_Managers 
	(managerID INTEGER,
	respZone VARCHAR(20),
	PRIMARY KEY (managerID));

grant select on Supply_Managers to public;


CREATE TABLE Manage 
	(zoneName VARCHAR(20) NOT NULL,
	managerID INTEGER,
	PRIMARY KEY (managerID),
	UNIQUE (zoneName),
	FOREIGN KEY (zoneName) references Zones,
	FOREIGN KEY (managerID) references Supply_Managers); 
 
grant select on Manage to public;
 

CREATE TABLE Company 
	(companyName VARCHAR(20), 
	cmpLocation VARCHAR(80) NOT NULL,
	email VARCHAR(40) NOT NULL,
	PRIMARY KEY (companyName));

grant select on Company to public;


CREATE TABLE Location_Method 
	(cmpLocation VARCHAR(50),
	deliveryMethod VARCHAR(30),
	PRIMARY KEY (cmpLocation));

grant select on Location_Method to public;


CREATE TABLE Contact_Info 
	(email CHAR(20), 
	phoneNum INTEGER,
	PRIMARY KEY (email));

grant select on Location_Method to public;


CREATE TABLE Provide_Supplies 
	(companyName VARCHAR(20),
	maxQuantityProvided INTEGER,
	category VARCHAR(20),
	PRIMARY KEY (companyName, category),
	FOREIGN KEY (companyName) references Company ON DELETE CASCADE);

grant select on Location_Method to public;


CREATE TABLE Contact 
	(managerID INTEGER,
	companyName VARCHAR(20),
	orderNum INTEGER,
	PRIMARY KEY (managerID, companyName),
	UNIQUE (orderNum),
	FOREIGN KEY (managerID) references Supply_Managers,
	FOREIGN KEY (companyName) references Company);
 
grant select on Contact to public;


CREATE TABLE Animal_Hospitals 
	(branchID INTEGER,
	hospitalName VARCHAR(40),
	PRIMARY KEY (branchID));

grant select on Animal_Hospitals to public;
 

CREATE TABLE Vets_Occupation 
	(zoneName VARCHAR(20),
	vetID INTEGER,
	branchID INTEGER NOT NULL,
	vetName VARCHAR(20),
	PRIMARY KEY (vetID),
	FOREIGN KEY (branchID) references Animal_Hospitals,
	FOREIGN KEY (zoneName) references Zones ON DELETE CASCADE);
 
grant select on Vets_Occupation to public;
 

insert into Animal_Carers
values(1, 'Ash', 'mamal_zone', 1, null, null);

insert into Animal_Carers
values(2, 'Nicole', 'bird_zone', 2, 'swan', null);

insert into Animal_Carers
values(3, 'Amanda', 'amphibian_zone', 3, 'frog', 'Mon,Wed,Fri');

insert into Animal_Carers
values(4, 'Jocab', 'arctic_zone', 4, null, 'Tue,Wd,Fri');

insert into Animal_Carers
values(5, 'Noah', 'mamal_zone', null, null, 'Tue');

insert into Animal_Carers
values(6, 'Abraham', 'reptile_zone', null, 'snake', null);


insert into Zones
values('mamal_zone', 120);

insert into Zones
values('bird_zone', 50);

insert into Zones
values('amphibian_zone', 18);

insert into Zones
values('arctic_zone', 20);

insert into Zones
values('reptile_zone', 30);


insert into Animal_BasicInfo
values(110, 'female', 1, to_date('2008-11-11', 'yyyy-mm-dd'), 'bird_zone', 'Common Ostrich');

insert into Animal_BasicInfo
values(111, 'female', 0, to_date('2020-11-13', 'yyyy-mm-dd'), 'arctic_zone', 'Polar Bear');

insert into Animal_BasicInfo
values(112, 'male', 1, to_date('2021-01-18', 'yyyy-mm-dd'), 'amphibian_zone', 'Glass Frog');

insert into Animal_BasicInfo
values(113, 'female', 0, to_date('2014-10-16', 'yyyy-mm-dd'), 'mamal_zone', 'Panda');

insert into Animal_BasicInfo
values(114, 'male', 1, to_date('2014-10-16', 'yyyy-mm-dd'), 'reptile_zone', 'Black Rat Snake');


insert into Animal_Diet
values('Black Rat Snake', 'rats, bird eggs');

insert into Animal_Diet
values('Polar Bear', 'trout, dog kibble, fortified meat-based commercial carnivore');

insert into Animal_Diet
values('Common Ostrich', 'small tortoises, seeds, succulant, oranges');

insert into Animal_Diet
values('Panda', 'bamboo shoots, bamboo leaf');

insert into Animal_Diet
values('Glass Frog', 'crickets, moths, flies');


insert into Zone_Shortage
values('mamal_zone', 'maintenance');

insert into Zone_Shortage
values('bird_zone', 'medicine');

insert into Zone_Shortage
values('bird_zone', 'cleaning');

insert into Zone_Shortage
values('arctic_zone', ' ');

insert into Zone_Shortage
values('reptile_zone', 'food');


insert into Take_Care_Of
values(113, 1, to_date('2014-10-16', 'yyyy-mm-dd'));

insert into Take_Care_Of
values(110, 2, to_date('2008-11-11', 'yyyy-mm-dd'));

insert into Take_Care_Of
values(112, 3, to_date('2021-01-18', 'yyyy-mm-dd'));

insert into Take_Care_Of
values(111, 4, to_date('2021-01-13', 'yyyy-mm-dd'));

insert into Take_Care_Of
values(113, 5, to_date('2014-10-16', 'yyyy-mm-dd'));

insert into Take_Care_Of
values(114, 6, to_date('2014-10-16', 'yyyy-mm-dd'));

insert into Take_Care_Of
values(111, 1, to_date('2022-01-16', 'yyyy-mm-dd'));


insert into Supply_Managers 
values(201, 'mamal_zone');

insert into Supply_Managers 
values(202, 'bird_zone');

insert into Supply_Managers 
values(203, 'amphibian_zone');

insert into Supply_Managers 
values(204, 'arctic_zone');

insert into Supply_Managers 
values(205, 'reptile_zone');


insert into Manage
values('mamal_zone', 201);

insert into Manage
values('bird_zone', 202);

insert into Manage
values('amphibian_zone', 203);

insert into Manage
values('arctic_zone', 204);

insert into Manage
values('reptile_zone', 205);


insert into Company
values('Anipet','19038 24 Ave, Surrey, BC V3Z 3S9, Canada','sales@anipet.com');

insert into Company
values('Zooplus','Germany','sales@zooplus.com');

insert into Company
values('Otto Environmental','11015 W Layton Ave A, Greenfield, WI 53228, US','sales@ottoevmn.com');

insert into Company
values('Zoo Med','12345 Ave, Toronto, ON Q7R7Y7, Canada','sales@zooMed.com');

insert into Company
values('Exotic Animal Supply','12345 Ave, Bikini Bottom','sales@exoAnisply.com');


insert into Location_Method
values('19038 24 Ave, Surrey, BC V3Z 3S9, Canada','Canada Post');

insert into Location_Method
values('Germany','German Airline, Ups');

insert into Location_Method
values('11015 W Layton Ave A, Greenfield, WI 53228, US','Alaska Airline, Ups');

insert into Location_Method
values('12345 Ave, Toronto, ON Q7R7Y7, Canada','Canada Post');

insert into Location_Method
values('12345 Ave, Bikini Bottom','Shipping Company A');


insert into Contact_Info
values('sales@anipet.com', 6045363367);

insert into Contact_Info
values('sales@zooplus.com', null);

insert into Contact_Info
values('sales@ottoevmn.com', 4145297780);

insert into Contact_Info
values('sales@zooMed.com', 4324758880);

insert into Contact_Info
values('sales@exoAnisply.com', 2264758475);


insert into Provide_Supplies
values('Anipet', 500, 'medicine');

insert into Provide_Supplies
values('Zooplus', 1000, 'cleaning');

insert into Provide_Supplies
values('Otto Environmental', 200, 'maintenance');

insert into Provide_Supplies
values('Zoo Med', 1000, 'medicine');

insert into Provide_Supplies
values('Exotic Animal Supply', 100, 'food');

insert into Provide_Supplies
values('Anipet', 2000, 'food');

insert into Provide_Supplies
values('Anipet', 50, 'maintenance');

insert into Provide_Supplies
values('Anipet', 150, 'cleaning');


insert into Contact
values(201, 'Anipet', 56789);

insert into Contact
values(202, 'Zooplus', 56790);

insert into Contact
values(203, 'Otto Environmental', 56791);

insert into Contact
values(204, 'Anipet', 56792);

insert into Contact
values(204, 'Zoo Med', 56793);


insert into Animal_Hospitals
values(401, 'BC Animal Hospital');

insert into Animal_Hospitals
values(402, 'Van Animal Hospital');

insert into Animal_Hospitals
values(403, 'North Road Animal Hospital');

insert into Animal_Hospitals
values(404, 'Mundy Animal Hospital');

insert into Animal_Hospitals
values(405, 'Central City Animal Hospital');


insert into Vets_Occupation
values('mamal_zone', 301, 401, 'Stephen');

insert into Vets_Occupation
values('bird_zone', 302, 402, 'Noah');

insert into Vets_Occupation
values('amphibian_zone', 303, 401,'Emily');

insert into Vets_Occupation
values('arctic_zone', 304, 403, 'Tracey');

insert into Vets_Occupation
values('reptile_zone', 305, 401, 'Ben');

insert into Vets_Occupation
values('mamal_zone', 306, 405, 'Ashley');