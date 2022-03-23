-- SELECT: select the animialID that carer with cid takes care of 
select animalID
from Take_Care_Of
where carerID = cid;

-- PROJECT: project all animialID together with its species and gender
select animalID, species, gender
from Animal_BasicInfo;

-- JOIN: find the location, delivery method, email and phonenum of a given company name
select companyName, C.cmpLocation, deliveryMethod, C.email, phoneNum
from Company C, Location_Method LM, Contact_Info I
where C.cmpLocation = LM.cmpLocation and C.email = I.email and C.companyName = cname;