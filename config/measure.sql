create schema formas;

use formas;

create table measure (
    id int primary key auto_increment,
    measurement varchar(45)
);

create table square (
    id int primary key auto_increment,
    color varchar(45),
    image varchar(45),
    id_measure int not null,
    height int not null,
    foreign key (id_measure) references measure(id)
);

create table circle (
    id int primary key auto_increment,
    color varchar(45),
    image varchar(45),
    id_measure int not null,
    radius float not null,
    foreign key (id_measure) references measure(id)
);

create table triangle (
    id int primary key auto_increment,
    color varchar(45),
    image varchar(45),
    id_measure int not null,
    leftSide int not null,
    rightSide int not null,
    bottomSide int not null,
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