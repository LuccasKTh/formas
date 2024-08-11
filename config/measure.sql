use formas;

create table measure (
    id int primary key auto_increment,
    measurement varchar(45)
);

create table square (
    id int primary key auto_increment,
    height int not null,
    backgroundType int not null,
    background varchar(45),
    color varchar(45),
    id_measure int not null,
    foreign key (id_measure) references measure(id)
);

insert into measure values(null, 'px');
insert into measure values(null, 'cm');
insert into measure values(null, 'mm');
insert into measure values(null, 'in');
insert into measure values(null, 'pt');
insert into measure values(null, 'pc');
insert into measure values(null, 'em');
insert into measure values(null, 'ex');
insert into measure values(null, 'ch');
insert into measure values(null, 'rem');
insert into measure values(null, 'vw');
insert into measure values(null, 'vh');