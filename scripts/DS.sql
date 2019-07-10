select * from calls;
select *, time_to_sec(timediff(dateTimeAnswered, datetimeReceived)) from calls where id = 8; idStatusEnd is not null;


update callhourlytotals set waitTime = waitTime + 

select *, hour(dateTimeEnded) as hour from calls;
select time_to_sec(timediff(dateTimeEnded, dateTimeAnswered)) from calls where idStatusEnd is not null;
select sec_to_time(avg(time_to_sec(timediff(dateTimeEnded, dateTimeAnswered)))) from calls;

select * from callhourlytotals;

delete from callhourlytotals where id = 1;
insert into callhourlytotals (date, hour, callsReceived, callsAnswered, callsEnded, handleTime, waitTime) values (now(), hour(now()), 0,0,0,0,0);
select * from callhourlytotals where date = date(now()) and hour = hour(now())

call spReceiveCall('6648888888', @result); select @result;

update callhourlytotals set callsReceived = callsReceived + 1 where date = date(now()) and hour = hour(now());

select idDefaultCallReceiveStatus, idDefaultCallAnswerStatus from config;

