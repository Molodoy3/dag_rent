declare namespace App.Data {
export type AccountData = {
id: number | null;
login: string;
password: string;
platform_id: number;
busy: string | null;
email: string | null;
passwordEmail: string | null;
price: number;
status: number | null;
comment: string | null;
user_id: number;
};
export type GameData = {
id: number | null;
name: string;
};
export type PlatformData = {
id: number | null;
name: string;
};
export type StatisticData = {
id: number | null;
price: number;
account_id: number;
added_at: string;
};
export type UserData = {
id: number | null;
name: string;
email: string;
role_id: number;
password: string;
};
}
