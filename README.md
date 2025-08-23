**🍽️ Restaurant Management API**


A RESTful API for managing a restaurant’s operations including **users, roles, tables, categories, menu items, orders, and order items**. Built with Laravel and Sanctum for secure authentication.

Supports role-based access control with granular permissions for **admin, manager, chef, and waiter roles**.

**🛠 Key Features**

**User Authentication & Authorization:** Register, login, and logout with API tokens via Laravel Sanctum.

**Role-Based Access Control:** Each role has specific permissions and abilities.

**User & Role Management:** Admins can manage users and assign roles dynamically.

**Table Management:** CRUD operations for restaurant tables.

**Menu Management:** Manage categories and menu items (create, update, delete).

**Order Processing:** Place, update, view, and delete orders linked to users and tables.

**Order Items Management:** Handle items within each order with quantities and pricing.

**Order Status Workflow:** Support statuses like pending, preparing, completed, served, and cancelled with role-specific control.

**Policies & Authorization:** Fine-grained access control policies for secure actions.

**Filtering & Pagination:** Efficient listing of resources with filters and paginated results.

**🛠 Technologies**

Laravel Framework 12

Sanctum for API token authentication

Eloquent ORM for database modeling

Policies for authorization and role-based access

PHPUnit for automated testing

**🧪 Testing**

This project includes feature tests to verify role-based access control, order workflows, and endpoint functionality.
