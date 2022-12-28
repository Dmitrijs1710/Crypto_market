create table user_coins
(
    id         int auto_increment
        primary key,
    product_id int          not null,
    user_id    int          not null,
    price      double       null,
    operation  varchar(255) not null,
    count      float        not null,
    name       varchar(255) not null,
    symbol     varchar(255) not null,
    logo       varchar(255) null,
    date       datetime     not null,
    userIdTo   int          null,
    open_short tinyint(1)   null
);

create table users
(
    id       int auto_increment
        primary key,
    email    varchar(256) not null,
    password varchar(256) not null,
    name     varchar(256) not null,
    balance  int          null,
    constraint email
        unique (email)
);


