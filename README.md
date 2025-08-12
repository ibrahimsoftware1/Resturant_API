**Restaurant Management API**

This is a RESTful API for managing a restaurant's operations including **users**, **roles**, **tables**,**categories**, **menu items**, **orders**, and **order items**.

Built with Laravel and **Sanctum for secure authentication**, this API supports role-based access control with granular permissions to ensure proper authorization for each user role **(admin, manager, chef, waiter)**.



**Key Features:**

**User Authentication & Authorization**: Register, login, and logout with API tokens managed by Laravel Sanctum.

**Role-Based Access Control**: Roles include admin, manager, chef, and waiter, each with specific permissions and abilities.

**User & Role Management**: Admins can manage users and assign roles dynamically.

**Table Management**: CRUD operations for restaurant tables.

**Menu Management**: Categories and menu items can be created, updated, and deleted.

**Order Processing**: Place, update, view, and delete orders, with orders linked to users and tables.

**Order Items**: Manage items within each order, including quantities and pricing.

**Order Status Workflow**: Support for statuses like pending, preparing, completed, served, and cancelled, controlled by user roles.

**Policies & Authorization**: Fine-grained access control policies ensure users can only perform allowed actions.

**Filtering & Pagination**: List endpoints support filtering (e.g., filtering orders by status) and pagination for efficient data retrieval.

**Technologies:**

Laravel Framework

Sanctum for API token authentication

Eloquent ORM for database modeling

Policies for authorization

