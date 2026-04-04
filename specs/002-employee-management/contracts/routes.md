# Route Contracts: Employee Management

## Web Routes (`routes/client.php`)

Grouped under:
- `middleware` => `['auth', 'role:client', 'subscription.check']`
- `prefix` => `client`
- `name` => `client.`

| Method | URI | Action | Route Name | Description |
|--------|-----|--------|------------|-------------|
| GET | `/dashboard` | `DashboardController@index` | `dashboard` | Client dashboard (metrics & banner) |
| GET | `/employees` | `EmployeeController@index` | `employees.index` | List employees (paginated) |
| GET | `/employees/create` | `EmployeeController@create` | `employees.create` | Form to add a new employee |
| POST | `/employees` | `EmployeeController@store` | `employees.store` | Save a new employee |
| GET | `/employees/{employee}` | `EmployeeController@show` | `employees.show` | View employee details |
| GET | `/employees/{employee}/edit` | `EmployeeController@edit` | `employees.edit` | Form to edit an employee |
| PUT/PATCH | `/employees/{employee}` | `EmployeeController@update` | `employees.update` | Save employee updates |
| DELETE | `/employees/{employee}` | `EmployeeController@destroy` | `employees.destroy` | Soft delete (archive) employee |
| GET | `/employees/import/form` | `EmployeeController@importForm` | `employees.import.form` | Form to upload Excel file |
| POST | `/employees/import` | `EmployeeController@import` | `employees.import` | Process Excel file upload |

## Secure File Routes (`routes/web.php`)

Grouped under:
- `middleware` => `['auth']` (tenant checking happens in the controller)

| Method | URI | Action | Route Name | Description |
|--------|-----|--------|------------|-------------|
| GET | `/files/employees/{employee}/{type}` | `EmployeeFileController@show` | `files.employee` | Serve private file (type: `national_id` or `contract`) |
