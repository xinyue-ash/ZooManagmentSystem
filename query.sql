-- SELECT: select the animialID that carer with cid takes care of 
select animalID
from Take_Care_Of
where carerID = cid(int);

-- PROJECT: project all animialID together with its species and gender
select animalID, species, gender
from Animal_BasicInfo;

-- JOIN: find the location, delivery method, email and phonenum of a given company name
select companyName, C.cmpLocation, deliveryMethod, C.email, phoneNum
from Company C, Location_Method LM, Contact_Info I
where C.cmpLocation = LM.cmpLocation and C.email = I.email and C.companyName = cname(varchar);

-- JOIN: find all animals with needVet = 1 in the zone that a given vet is responsible for 
select animalID
from Animal_BasicInfo A, Vets_Occupation V
where A.zoneName = V.zoneName and needVet = 1 and vetID = vid(int);

-- AGGREGATION: count the number of animals for a given species
select count(*)
from Animal_BasicInfo
where species = sp(varchar);

-- NESTED AGGREGATION: under each supply category, find companies with highest maxQuantityProvided
with Temp(category, maxNum) as
    (select category, max(maxQuantityProvided)
    from Provide_Supplies
    group by category)
select P.companyName, Temp.category, Temp.maxNum
from Provide_Supplies P, Temp
where P.category = Temp.category and P.maxQuantityProvided = Temp.maxNum;

-- DIVISION: given current supply shortages, find companies that can provide all corresponding supplies
select C.companyName from Company C
where not exists
    (select supplyShortage from Zone_Shortage where supplyShortage <> ' '
    minus
    select category from Provide_Supplies P where P.companyName = C.companyName);