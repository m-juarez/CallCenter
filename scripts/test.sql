-- test
call spLoginAgent(1001, 2001, 1, @result); select @result;
call spReceiveCall('6641111111', @result); select @result;
call spReceiveCall('6642222222', @result); select @result;
call spLoginAgent(1002, 2002, 2, @result); select @result;
call spReceiveCall('6643333333', @result); select @result;
call spLoginAgent(1003, 2003, 3, @result); select @result;
call spReceiveCall('6644444444', @result); select @result;
call spLoginAgent(1004, 2004, 4, @result); select @result;
call spReceiveCall('6646666666', @result); select @result;
call spEndCall(3,1, @result); select @result;
CALL spReceiveCall('4847487')
-- sessions
select * from calls;
-- session log
select sl.id, sl.idSession, sl.dateTimeStart, sl.dateTimeEnd, sl.idStatus, ss.description 
from sessionLog as sl join statusSessionLog as ss where sl.idStatus = ss.id order by sl.id;
-- calls
select c.id, c.dateTimeReceived, c.dateTimeAnswered, c.dateTimeEnded, c.phoneNumber, c.idSession, c.idStatus, 
sc.description as statusDescription, c.idStatusEnd, sce.description as statusEndDescription,
sec_to_time(time_to_sec(timediff(dateTimeEnded, dateTimeAnswered))) as handleTime,
sec_to_time(time_to_sec(timediff(dateTimeAnswered, datetimeReceived))) as waitTime
from calls as c left join statuscall as sc on c.idStatus = sc.id  
left join statusCallEnd as sce on c.idStatusEnd = sce.id;
-- hourly totals 
select * from callHourlyTotals;

select * from calldailytotals

select * from calls;