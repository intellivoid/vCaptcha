create table if not exists instances
(
    id                     varchar(32)          not null comment 'The Unique ID of the captcha instance'
        primary key,
    name                   varchar(256)         null comment 'The name of the captcha instance (URL Encoded)',
    captcha_type           varchar(64)          not null comment 'The type of captcha that this instance uses',
    owner_id               varchar(64)          not null comment 'The ID of the owner that manages this captcha instance',
    secret_key             varchar(48)          not null comment 'The secret key for the captcha instance (API Key)',
    enabled                tinyint(1) default 1 not null comment 'Indicates if the instance is enabled or not',
    firewall_options       blob                 not null comment 'ZiProto encoded object of the firewall options that this captcha has enabled',
    created_timestamp      int        default 0 not null comment 'The Unix Timestamp for when this instance was created',
    last_updated_timestamp int        default 0 null comment 'The Unix Timestamp for when this instance was last updated',
    constraint instances_id_owner_id_uindex
        unique (id, owner_id),
    constraint instances_id_uindex
        unique (id)
)
    comment 'Table for housing captcha instances';

create index instances_enabled_index
    on instances (enabled);

create index instances_owner_id_index
    on instances (owner_id);

