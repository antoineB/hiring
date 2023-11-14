CREATE TABLE sqlite_sequence(name,seq);
CREATE TABLE user(id integer primary key);
CREATE TABLE vehicle(id integer primary key autoincrement, plate_number text not null unique check(plate_number != ''));
CREATE TABLE fleet(id integer primary key autoincrement, user_id integer not null references user(id));
CREATE TABLE fleet_vehicle_location(id integer primary key autoincrement, fleet_id integer not null references fleet(id), vehicle_id integer not null references vehicle(id), latitude real default null, longitude real default null, altitude real default null/*, check((latitude is null and longitude is null and altitude is null) or (latitude is not null and longitude is not null and altitude is not null and latitude <= 90.0 and latitude >= -90.0 and longitude <= 180.0 and longitude >= -180.0 and altitude > 0.0))*/,  unique(fleet_id, vehicle_id) on conflict replace);

-- J'abandonne la contrainte sur les latitude, longitude, altitude avec les null cela ne marche pas avec PDO.
