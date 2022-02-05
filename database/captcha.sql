create table if not exists captcha
(
    id                   varchar(126) not null comment 'The unique public ID for the captcha instance'
        primary key,
    captcha_instance_id  varchar(32)  not null comment 'The captcha instance that generates and owns this captcha',
    captcha_type         varchar(64)  null comment 'The type of captcha that is shown to the user',
    value                varchar(256) null comment 'The value(s) used to generate the captcha to the user',
    answer               varchar(256) null comment 'The expected answer from the user or software',
    host                 varchar(128) null comment 'The host that initialized the captcha instance',
    fail_reason          varchar(64)  null comment 'The current fail reason of the captcha',
    created_timestamp    int          null comment 'The Unix Timestamp for when this captcha was first created',
    expiration_timestamp int          null comment 'The Unix Timestamp for when this captcha will be expired',
    constraint captcha_id_uindex
        unique (id),
    constraint captcha_instances_id_fk
        foreign key (captcha_instance_id) references instances (id)
)
    comment 'Table for housing generated captcha instances';

create index captcha_fail_reason_index
    on captcha (fail_reason);

create index captcha_host_index
    on captcha (host);

