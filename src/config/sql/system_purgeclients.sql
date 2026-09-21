truncate table d_registration;

delete from c_printqueue where client_id > 5;
delete from c_esbbroadcaster where client_id > 5;
delete from c_esb where client_id > 5;
delete from c_esblistener where client_id > 5;

delete from d_profile_permission where client_id > 5;
delete from d_user_profile where client_id > 5;
delete from d_profile where client_id > 5;
delete from d_usersetting where client_id > 5;
delete from d_user where client_id > 5;
delete from d_account where client_id > 5;
delete from d_device where client_id > 5;
delete from d_document where client_id > 5;
delete from d_devicelog where client_id > 5;
delete from c_settingcontext where client_id > 5;
delete from c_settingvalue where client_id > 5;
delete from c_settingvaluestore where client_id > 5;

update d_client set client_id = 4, data_client_id = 4;
delete from d_client where id > 5;
