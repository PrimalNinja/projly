# Projly v20260922

> **Now open source.** Both AWAF and Projly have been open sourced. Some functionality had to be removed to do this, so you may hit bugs where something depended on a part that was taken out.
>
> The system is highly modular. JavaScript modules live in `app/modules/` and PHP modules live in `ws/modules/`. Many can be switched on or off, mostly through the [`/config` tool](#configuration-tool-config).

Projly is a multi-tenant project management and issue tracking suite. It handles projects, sprints, tasks, bugs, requirements, support issues, wish lists, time and resource tracking, rosters, campaigns and R&D grant records.

Projly is an application built on **AWAF (Agility Web Application Framework)**, a low-code platform. AWAF applications aren't separate programs sitting on top of the framework — they become part of the system. So a Projly install is AWAF plus the Projly application, and you get the whole platform with it:

- the development tools (form builder, report builder, entities, permissions, nav items, plugins, system builds)
- security, users, profiles and permissions
- products, the cart and payment gateways
- registration
- documents, printing, imports, integrations, messaging, work queue and the rest of the configuration menus

Everything is built from metadata. Forms, lists, menus, permissions and products are all records in the database, not hard-coded screens. You change them from inside the running application.

**Which part is which:** the *Projly* menu group, the *Configuration - Projly* menu group and the `projly` server module are Projly. Everything else — the other configuration menus, Development, Developer, Security, System, My Account, the store and registration — is AWAF.

- **Frontend:** JavaScript — AWAFOS, a windowed desktop that runs in the browser, with a mobile mode
- **Backend:** PHP 5.4+ and PHP 7, JSON web services
- **Database:** MySQL (InnoDB), three databases per install: main, history and temp

---

## Contents

- [Projly features](#projly-features)
- [AWAF: how it works](#awaf-how-it-works)
  - [Metadata-driven forms](#metadata-driven-forms)
  - [Time machine forms (full history)](#time-machine-forms-full-history)
  - [Multi-tenancy](#multi-tenancy)
  - [Logging in](#logging-in)
  - [The built-in tenant areas](#the-built-in-tenant-areas)
  - [Owner and clients](#owner-and-clients)
  - [Permissions, profiles and menus](#permissions-profiles-and-menus)
  - [Products and the built-in store](#products-and-the-built-in-store)
  - [The three databases](#the-three-databases)
  - [Document repositories](#document-repositories)
  - [Security](#security)
- [Entity layer](#entity-layer)
  - [Web service function names](#web-service-function-names)
  - [Tables per entity](#tables-per-entity)
  - [Permissions by naming](#permissions-by-naming)
  - [Every write is tenant-scoped and transactional](#every-write-is-tenant-scoped-and-transactional)
  - [Form data options](#form-data-options)
  - [Special IDs](#special-ids)
  - [Lists](#lists)
  - [Per-entity overrides](#per-entity-overrides)
  - [Custom operations](#custom-operations)
  - [Code convention: matching functions](#code-convention-matching-functions)
- [Data access](#data-access)
  - [database.php](#databasephp)
  - [Data accessors (the "ORM")](#data-accessors-the-orm)
  - [Documents: the first data accessor user](#documents-the-first-data-accessor-user)
- [Frontend (AWAFOS)](#frontend-awafos)
  - [Forms](#forms)
  - [Low-code forms need no code](#low-code-forms-need-no-code)
  - [Form-specific logic](#form-specific-logic-appmodulesentitylogic)
  - [UI components](#ui-components-appinc-osutils-js)
  - [Code naming](#code-naming)
- [Building the JavaScript](#building-the-javascript)
- [Web service](#web-service)
  - [Entry points](#entry-points)
  - [How it stays small](#how-it-stays-small)
  - [Request format](#request-format)
  - [Native iOS and Android apps](#native-ios-and-android-apps)
  - [Session handler](#session-handler)
- [Folder layout](#folder-layout)
- [Feature groups](#feature-groups)
  - [Remote printing](#remote-printing)
- [Configuration tool (/config)](#configuration-tool-config)
- [Environment configuration files](#environment-configuration-files)
- [Modules](#modules)
- [Requirements](#requirements)
- [Installation](#installation)
- [Roadmap](#roadmap)
- [Third-party components](#third-party-components)
- [License](#license)
- [Comparison](#comparison)

---

## Projly features

These are the Projly application itself — the *Projly* and *Configuration - Projly* menu groups.

**Project management**
- Projects with type, status, priority, stakeholder, budget, start/target/end dates, and linked (associated) projects
- Sprints with planned and actual start/end dates and estimated hours
- Tasks with priority, status, dependencies (`depends on task`), allocated hours, and investigate / fix / test / check-in stages
- Ongoing tasks as well as one-off tasks
- Project dashboard

**Issue, bug and support tracking**

All issues are one entity (`PROJECTISSUE`) with an issue type. The menu gives you filtered lists over it:
- All Issues
- Bugs / Bugs (Completed)
- Requirements / Requirements (Completed)
- Support Issues / Support Issues (Completed)
- Tasks / Tasks (Completed)
- Wish List

Each issue records who reported it, who it is assigned to, the sprint, priority, status, reported / target / addressed dates, estimated vs actual hours and minutes, two URLs, and image and file attachments.

**People, resources and time**
- Stakeholders (linked to accounts), participants and resources, each with a participant or resource type
- Cost categories (cost per hour / day) and billing categories (charge-out per hour / day) on participants and resources, so time can be costed and billed
- Participant activity and resource activity logs (date, start/end time, total hours) against tasks and rosters
- Rosters and roster templates: planned vs actual clock-on/off, breaks, cancellations, manager sign-off and HR sign-off

**Campaigns**
- Campaigns with type, status, raised by / managed by and dates
- Campaign tasks with priority, status and assignment

**R&D grants and research records**
- R&D grants: applied / approved / granted dates, period, estimated vs granted amount, application details
- R&D questions tied to a project and grant, with research, hypothesis, test, observation and result records to back up the claim

**All the configurable lookups**

Issue types, issue statuses, issue priorities, project types, statuses and priorities, task statuses and priorities, participant types, resource types, campaign types and statuses are all editable lists under *Configuration - Projly*.

---

## AWAF: how it works

Everything in this section is AWAF. Projly uses it as-is, and any other AWAF application gets the same behaviour.

### Metadata-driven forms

Every screen is a form layout stored as JSON. A form is a list of sections, and each section is a list of fields. Every form has:

| Section | Purpose |
|---|---|
| `FORMHEADER` | Entity code, form code, description, enabled flag, **form version** |
| `DATAHEADER` | **Data version** of the record |
| `INTERNALUSEFORMHEADER` | Submitted date/time, client and user |
| `DATA` | The actual fields the user sees |

Each field carries its own properties: name, label, data type, length, required, read-only, searchable, sortable, default value, CSS classes and a data source for dropdowns.

Field data types:

`d_text` `d_multilinetext` `d_number` `d_date` `d_time` `d_yesno` `d_list` `d_multilist` `d_password` `d_url` `d_document` `d_image` `d_html` `d_texthtml` `d_codeeditor` `d_representation` `d_version` `d_heading` `d_spacer` `d_description` `d_abnlookup` `d_barcode` `d_button` `d_chart` `d_gps` `d_metadata` `d_predictivetext` `d_relatedlinks`

A `d_list` field points to another entity with a source string, for example:

```
PROJECTISSUESTATUS|['description']|[{'field':'isenabled','value':'Y'}]
```

That means: pull from `PROJECTISSUESTATUS`, show `description`, and only show enabled rows.

**Storage:** each record is saved as the full JSON document (`jsondata`) plus a set of real columns:
- standard columns on every table: `id`, `client_id`, `data_client_id`, `entity_id`, `dataentity_id`, `code`, `description`, `is_enabled`, `modifyuser`, `modifydatetime`
- a `<field>_id` foreign key column for every list field
- the searchable / sortable fields copied out into their own columns, named `<section-guid>_<fieldname>`, so they can be indexed, filtered and sorted

When a form layout is changed and published, the column changes it needs are queued as **schema changes** (see *System → Schema Changes (Pending / Done)*).

Forms are built with the built-in **Form Builder**. Reports are built the same way with the **Report Builder** (report layouts, report section templates and report data types).

### Time machine forms (full history)

Most forms are full time machines. Every save, and every delete, writes the previous version of the record to the history database (`h_<entity>` tables, keyed by `entitydata_id`), including the whole JSON document. See [Form data options](#form-data-options) for how stepping through versions works.

When you step back through a record's history you see the data as it was **and the form as it was** at that time. Fields that were added later disappear as you go back, and fields that have since been dropped reappear. Both the form version and the data version are stored with each record, so the form layout and data always match.

On top of that there is a data audit log and a data change log (`c_datachange`).

This was built for a government compliance department, where you have to be able to show exactly what was on a form, and what the form looked like, at any point in the past.

### Multi-tenancy

Every row carries two tenant columns:

| Column | Meaning |
|---|---|
| `client_id` | The tenant that currently holds the record |
| `data_client_id` | The tenant that originally submitted it |

All lists, forms and lookups are scoped to the tenant. The exception is shared reference data (states, suburbs, genders, payment methods, the Projly issue/task/project types, statuses and priorities, and so on). These are listed in the `IGNORECLIENT_...` constants in `ws/inc-constants.php` and are visible to every tenant. Tenants can also have branches, with users assigned to branches and a default branch per user.

**Passing data between tenants.** A tenant can fill in a form and submit it to another tenant. The receiving tenant can accept it or return it. `data_client_id` keeps track of who first submitted the record, so the original submitter is never lost, however many times the record changes hands. On the server this is the `submittoclientid` parameter on form data add, update and fetch: the record is saved under the target tenant, with the submitter kept as the data client.

### Logging in

Login has three fields, not two:

1. **Client code** — the tenant
2. **Login or email address**
3. **Password**

The same login name (e.g. `admin`) can exist in every tenant. The client code decides which one you are.

### The built-in tenant areas

AWAF ships with four preset tenant areas, plus the client type that every new tenant gets. Default logins:

| Client code | Login | Password | What it's for |
|---|---|---|---|
| `public` | `public` | `public` | What the public is logged in as |
| `public` | `admin` | `passw0rd` | Administering the public area |
| `default` | `admin` | `passw0rd` | Where the default profiles are set up. New clients get these when they are created |
| `system` | `sysadmin` | `passw0rd` | The system's own configuration. The login is `sysadmin`, not `admin`, on purpose, so it isn't mixed up with the other admin logins |
| `owner` | `admin` | `passw0rd` | The system owner's area |
| `batch` | `batch` | `passw0rd` | Used by batch processing |
| *(each client)* | `admin` | `passw0rd` | Every client gets an admin login when it is created, either through registration (if enabled) or by the owner creating the client. It gets the `Client Administrator` profile |

The special client codes, the new-client admin login, password and profile, and the default profiles are all constants in `ws/inc-settings.php` and `ws/inc-app-projlyclient.php`.

**Change all of these passwords straight after installing.**

**The public tenant** lets you expose rich functionality to the public without them registering. Technically the public are still logged in (as `public`/`public`), so everything they do is logged like any other user.

Which menu map each area uses:

| Area | Menu map | Contents |
|---|---|---|
| Public | `getNavMapPublic` | Empty by default. Exposed features are added here |
| System (sysadmin) | `getNavMapSysAdmin` | All configuration, security, system and development tools |
| Developer | `getNavMapDeveloper` | The sysadmin menu plus the full developer tools: form builder, report builder, entities, permissions, nav items, plugins, system builds. The developer tiles only appear when `developer` is `TRUE` in the environment file |
| Owner, default and batch | `getNavMapSysOwner` | Application and system configuration, security, system |
| Client | `getNavMapClient` | Every new tenant. The day-to-day application features, driven by what they have bought and their permissions |

### Owner and clients

The **owner** runs the system. **Clients** are the tenants the owner serves. AWAF has already been used for all of these in production:

| Owner | Clients |
|---|---|
| A freight company | Customers entering consignments |
| The company whose systems are being managed (Projly) | Their customers, who they support |
| An online store owner | The customers |
| A government department | The public |

New clients register through the registration flow (individual or employer registration types, optional salesperson code, terms and privacy acceptance), or the owner creates them. Either way they start with the profiles set up in the `default` tenant.

### Permissions, profiles and menus

- **Permissions** are records grouped into permission categories. They can be marked as sys-admin only, non-sys-admin, or licensed (need a product to unlock).
- **Profiles** are sets of permissions. Users get one or more profiles.
- **Menus** are built from a menu map (groups of tiles). Each tile lists the permissions it needs, and the whole group only shows if the user has the group's `VW_...GROUP` permission. So one menu definition serves everyone and each user only sees what they are allowed to use.
- Each tile opens a form, a list, a dashboard or a widget. Lists can carry a fixed filter, which is how Bugs, Support, Requirements and the rest are all views over the one issue entity.

### Products and the built-in store

Products are metadata too. A product has:
- product type, price ex-GST and inc-GST, display order
- period and grace period (for subscriptions)
- rules, requirements, filter and behaviour category
- a **profile list** — buying the product grants those profiles, and through them the permissions and menu items
- an optional work queue item type (so a purchase can create work)
- renewal and expiry groups

Products overlap in the menus. Two products can unlock the same menu item, and the user sees it if any of their profiles allow it.

The store includes:
- cart and checkout (*View Cart*)
- pending transactions, transactions, transaction history, receipts (tax invoices) and payments
- negotiated rates and negotiated discounts per client and product
- refunds
- salesperson codes and a log of sales against them
- *My Products* showing what a client owns, with purchase and expiry dates

**Payment gateways are plugins.** Included:
- Dummy gateway (testing)
- PayPal (sandbox and production)
- eWAY
- NAB Transact
- Generic credit card provider

### The three databases

| Database | Prefix | Holds |
|---|---|---|
| Main | `d_` (data) and `c_` (core/system) | Live data |
| History | `h_` | Every previous version of every record — this is what the time machine reads |
| Temp | — | Imports and sessions |

There are two sets of these three databases: **system** and **client**. By default (`ENABLE_CLIENTDATABASES` = `FALSE`) everything runs from the system set. When it is `TRUE`, the login checks which set the tenant lives in and the session uses that set from then on, so client tenants can be kept in their own databases, apart from the system tenants.

### Document repositories

There can be more than one document repository. Each one has a path, a retention type, a document count and storage used.

| Repository | Folder |
|---|---|
| System | `repository-system/` (`documents/` and `templates/`) |
| Client | `repository-client/` (`documents/` and `templates/`) |

- Documents are stored in cluster folders, 3 levels deep by default (`USECLUSTERPATH3`). Set it to `FALSE` for 5 levels, which suits websites better.
- Most documents are accessed by GUID, not by a readable filename. See [Documents](#documents-the-first-data-accessor-user) for how files are stored.
- The repository folders need to be readable by the web server, but **turn directory listing off** on them.
- Retention types include document, print job, system, temporary and user.

### Security

- **Authentication** is plugin based (authentication plugins and types). LDAP is supported: it can create users from LDAP users, create profiles from LDAP groups, and authenticate against an LDAP client.
- **Two-factor authentication** with Google Authenticator (`enable_2fa`).
- **Password and ID encryption** are plugins too, set in `ws/inc-constants.php` (1-way password, 2-way password, 2-way ID and licence decryption).
- **Secure IDs** (`SECURE_IDS`): record IDs are encrypted before they are sent to the browser, or replaced with session GUIDs if `SECURE_IDS_WITH_GUIDS` is on. The browser never sees a real database ID.
- **Sessions** can be stored in the database (`SESSION_IN_DATABASE`), in the temp database's `t_sessions` table. See [Session handler](#session-handler) for picking the right one for your PHP version.
- Every request checks the `SERVER_DISABLED` system setting (with a reason shown to users) and that the database schema version matches the version the code expects. Either one stops the request before anything runs.
- Optional "stay logged in" cookie (`ALLOW_STAY_LOGGEDIN`).
- Logging in the same browser twice forces a logout of the earlier session.
- Every web service call can be logged. Separate logs in `ws/log/` cover API calls, builds, debug, SQL, PHP errors, missing files, payments, printing, repository, routing, security and settings.

---

## Entity layer

The `entity` server module does the reading and writing for every entity and form. There are two kinds of data:

| Kind | What it is | Functions |
|---|---|---|
| **Entity data** | Plain entities (lookups, config, anything not built as a form) | `actionEntityDataAdd` / `Update` / `Delete` / `Fetch` / `List` |
| **Form templates** | Form layouts, stored in `d_systemform` | `actionFormAdd` / `Update` / `Delete` / `Fetch` |
| **Form data** | Filled-in forms, stored in each form entity's own table | `actionFormDataAdd` / `Update` / `Delete` / `Fetch` / `List` |

Plus `actionEntityDataHeadersFetchByEntityCode` / `actionFormDataHeadersFetchByEntityCode` (list columns and flags), `actionEntityOperationsFetchByEntityCode` (the operations to show on a list), `actionEntityCustomOperationExecute`, `actionEntityReorder` (drag to reorder) and `actionEntitySearchByCode`.

### Web service function names

These are the names to send as `function` to `ws/server.php` (from `ws/modules/entity/inc-entry.php`):

| Function | Does |
|---|---|
| `entity_formfetch` | Fetch a form layout (blank form to fill in, or the layout for the builder) |
| `entity_formadd` / `entity_formupdate` / `entity_formdelete` | Add, update, delete a form layout (developers) |
| `entity_formdatafetch` | Fetch a filled-in form, or one of its history versions |
| `entity_formdataadd` / `entity_formdataupdate` / `entity_formdatadelete` | Add, update, delete filled-in forms |
| `entity_formdatalist` | List filled-in forms |
| `entity_formdataheadersfetchbyentitycode` | List columns and flags for a form entity |
| `entity_entitydatalist` / `entity_entitydatadelete` | List and delete plain entity data |
| `entity_entitydataheadersfetchbyentitycode` | List columns and flags for a plain entity |
| `entity_entityoperationsfetchbyentitycode` | The operation buttons for an entity's list |
| `entity_customoperationexecute` | Run a custom operation |
| `entity_entityreorder` | Drag-and-drop reorder |
| `entity_entitysearchbycode` | Search entities by code (developers) |
| `entity_dataformentitylist` | List data form entities |

Every module has an `inc-entry.php` like this: a whitelist mapping each function name to the file to load and the PHP function to call. A name that isn't in a module's list is never dispatched.

### Tables per entity

Creating an entity creates its tables; deleting it drops them:

| Table | Holds |
|---|---|
| `d_<entity>` | Live data: the standard columns plus `jsondata`, with the exposed searchable columns added as forms are published |
| `h_<entity>` | History: the same columns plus `entitydata_id` (the live row's ID). No foreign keys, so history survives anything it points at being deleted |
| extension table | Only for extended entities (`ISEXTENDED`): `id`, `client_id`, `is_exposed` and the extra columns, joined 1-to-1 to the live row |

Deleting an entity also removes its operations, the permissions for those operations (and their links to profiles), and its form layouts and their history.

When a form layout is saved, its searchable fields are exposed as real columns and the values are copied out of `jsondata` into those columns for every existing row (in the background for extended entities).

### Permissions by naming

Permission checks come from the entity code, so a new entity gets its permissions just by being named:

| Action | Permission |
|---|---|
| View / list / fetch | `VW_<ENTITY>` |
| Add | `ADD_<ENTITY>` |
| Update | `EDT_<ENTITY>` |
| Delete | `DEL_<ENTITY>` |
| Add / update / delete a form template, search entities | `DEVELOPER` |

### Every write is tenant-scoped and transactional

- Data is always read and written for the logged-in tenant (`server_loggedin_clientid` from the session), never a tenant ID from the browser. The exceptions are the shared reference data lists and `submittoclientid` for passing forms between tenants.
- IDs coming in from the browser are decrypted and IDs going out are encrypted (see Secure IDs under [Security](#security)).
- Every add, update and delete runs in a database transaction.
- A delete blocked by a foreign key returns a readable message saying what the record is still used by.
- Every update and every delete writes the previous version to history. Only the entities in `IGNOREHISTORYONDELETE_ENTITES` (by default just `CLIENT`) skip history on delete.
- Form data views, lists, adds and deletes are written to the audit log, so there is a record of who **looked at** a form, not only who changed it.

### Form data options

`actionFormDataFetch` takes some extra parameters:

| Parameter | Does |
|---|---|
| `historyid` | Fetch an old version from the history database — this is the time machine |
| `uselatestform` | Show old data in the current form layout instead of the layout it was saved with |
| `submittoclientid` | Fetch a form that was submitted to another tenant |
| `formentitydatacode` | Fetch by code instead of ID |
| `relativeid`, `relative`, `relationship` | Parent / child data (e.g. `relationship: 'children'`) |

**Stepping through history.** Every form data fetch comes back with `previd` and `nextid` — the (encrypted) history IDs either side of the version being shown. The UI sends one of those back as `historyid` to step backwards or forwards. With no `historyid` you get the current version, with `previd` pointing at the most recent history row.

**Old data in a new layout.** With `uselatestform`, if the current layout's `FORMVERSION` is higher than the version the record was saved with, the saved values are copied into the new layout. Without it, the record is shown in the layout it was saved with.

**Form layouts as files.** When `FETCHSYSTEMFORMSFROMFILE` is `TRUE`, form layouts are loaded from `ws/modules/entity/forms/<formcode>.json`, falling back to the copy in the database. The *build file JSON* operations on System Forms write these files out from the database (one form, or all of them), so form layouts can live in source control.

### Special IDs

These words can be used in place of an ID in filters and parameters. The server swaps in the real value:

| Word | Becomes |
|---|---|
| `MYCLIENT` | The logged-in tenant |
| `MYSELF` | The logged-in user |
| `MYACCOUNT` | The logged-in user's account |
| `MYDEVICE` | The current device |
| `MYSELFEMPLOYEE` | The logged-in user as an employee |
| `FORMFRAGMENT` | A form fragment |

These are for convenience in filters, not for security.

### Lists

- `fixedfilter` — filters set by the menu tile (this is how Bugs, Support, etc. filter the one issue entity)
- `passedfilter` — filters passed in from another form
- `filter` — the user's search
- `order`, `offset`, `limit` — sorting and paging. `limit` is capped at `NONAJAXGRIDLIMIT` (500)
- Paging selects the IDs first and joins back to the table, so big offsets stay fast.
- Extended entities (`ISEXTENDED`) are joined to their extension table automatically.
- Drag-to-reorder uses the entity's `DRAGORDERFIELD`, and `DRAGORDERGROUPFIELD` when reordering within a group.
- **Only whitelisted fields can be sorted or filtered on**: the list in `ws/modules/entity/inc-idwhitelist.php` plus the form's own searchable fields. Anything else sent from the browser is ignored. `dataentity_id` is only allowed for developers.
- **Exclusive lists** (`exclusive: true`) show the records *not* already linked to a parent, e.g. the permissions a profile doesn't have yet. This is what the *Add to* dialogs use.
- Some entities have extra automatic restrictions (constants in `ws/inc-constants.php`):

| Constant | Restriction |
|---|---|
| `ENTITIESFILTEREDBYAPPLICATION` | Only rows whose `applications` column is empty or includes this app's `APP_CODE` (CMS pages and message templates by default) |
| `ENTITIESFILTEREDBYBRANCH` | Only rows in the branches the user belongs to |
| `ENTITIESFILTEREDBYDEVICE` | Public users only see rows for their own device |

### Per-entity overrides

All the low-code functionality goes through this entity module. A form or application built in the Form Builder needs no code at all: the default behaviour handles storing, listing, searching, history and permissions.

When an entity needs something different, you drop a PHP file named after the entity into one of the folders under `ws/modules/entity/`. Every folder has a `default` that is used when there's no file for the entity, so these are **overrides**. Write only the ones you need.

| Folder | Overrides |
|---|---|
| `actions/` | Custom operation actions (the buttons on a list) — see [Custom operations](#custom-operations) |
| `beforeafter/` | Event handlers around display, list, add, update and delete |
| `dataaccess/` | Generated data accessors for calling an entity from your own PHP |
| `filters/` | Counting, the fetch SQL, filter decoding and search |
| `flags/` | List behaviour flags |
| `formatters/` | What each row in a list returns |
| `forms/` | Form layouts as JSON files |
| `headers/` | List columns |

Other modules don't have to use any of this. They can read and write their own hard-coded tables like any PHP app. AWAF didn't start out low-code; it became low-code over the years, and both ways still work side by side.

#### `beforeafter/<entity>.php` — event handlers

```php
function beforeDisplayAddUpdate_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $blnUpdate_a, $strMode_a)
function beforeAddUpdate_permission(...)          // returns the JSON to save
function afterAddUpdate_permission(...)           // returns the JSON
function afterAddUpdateExpose_permission(...)     // returns the JSON
function beforeDelete_permission(...)
function afterDelete_permission(...)
function beforeSelect_permission($objConn_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $strFormDataID_a)
function beforeList_permission($objConn_a, $strClientID_a)
```

| Handler | Use it for |
|---|---|
| `beforeDisplayAddUpdate_` | Default values in the JSON before the user starts filling in the form |
| `beforeAddUpdate_` | Changing the JSON before it's stored (it's stored for you), and validation such as uniqueness. Put unique indexes on field combinations that must be unique too |
| `afterAddUpdate_` | Populating other tables and manually created fields |
| `afterAddUpdateExpose_` | **Anything that relies on the exposed (searchable) columns** — they are only filled in by this point |
| `beforeDelete_` / `afterDelete_` | Clean-up around a delete. Called once per record |
| `beforeSelect_` | Before a fetch. Can return SQL to replace it, e.g. to build `jsondata` on the fly for a row that doesn't have it |
| `beforeList_` | Before a list is read |

Event order on save: **before** handlers → JSON and common columns saved → **after** handlers → exposed field values copied into their columns → **after expose** handlers.

Example — permission codes are forced to upper case before saving, and renaming a permission also renames the entity operation that uses it:

```php
function beforeAddUpdate_permission($objConn_a, $arrRow_a, $strEntityCode_a, $strFormEntityCode_a, $strClientID_a, $arrJSONData_a, $strFormDataID_a, $strRelativeID_a, $strRelative_a, $strRelationship_a, $blnUpdate_a)
{
    $arrJSONField = formFieldGetBySectionCodeFieldCode($arrJSONData_a, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE");
    return formValueUpdateBySectionCodeFieldCode($arrJSONData_a, "0000eae3-e5b8-4ebb-a3a8-50220cee15d5", "CODE", ffeu($arrJSONField['p_value']));
}
```

#### `filters/<entity>.php` — list SQL and search

```php
function getCount_permission(...)    { return ''; }   // '' = use the default count SQL
function getData_permission(...)     { return ''; }   // '' = use the default fetch SQL
function decodeFilter_permission($objConn_a, $strFilter_a, $arrFixedFilter_a) { ... }
function getFilter_permission($objConn_a, $strAppend_a, $strFilter_a, $arrSearchableFields_a, $arrFixedFilter_a, $arrPassedFilter_a) { ... }
```

- `getCount_` / `getData_` return your own SQL, or an empty string to keep the default.
- `decodeFilter_` fixes up filter values before use. Most often it decrypts secured IDs, e.g. `profile_id` when a permission is picked from the *Add to profile* popup.
- `getFilter_` builds the `WHERE` for the user's search text, usually a `like` across the searchable fields plus `code`, `description` and `is_enabled`.

#### `formatters/<entity>.php` — list rows

Returns the array for one row. The standard fields are `id` (secured), `rownum`, `formentitycode`, `code`, `description`, `version`, `isenabled`, `modified`, `recordcount` and `limited`. Extra columns come from the exposed columns, named `<section guid>_<field>` with the dashes turned into underscores:

```php
"displayorder" => $arrRowUnformatted_a['ffb775d2a9_59b7_453b_91ce_7621548ffe81_displayorder'],
```

#### `headers/<entity>.php` — list columns

```php
function getHeader_branch($objConn_a)
{
    $arrResult = array();
    $arrResult[] = array('id' => 'code',        'field' => 'code',        'name' => 'Code',        'sortable' => 'Y');
    $arrResult[] = array('id' => 'description', 'field' => 'description', 'name' => 'Description', 'sortable' => 'Y');
    $arrResult[] = array('id' => 'isenabled',   'field' => 'isenabled',   'name' => 'Enabled',     'sortable' => 'Y');
    return $arrResult;
}
```

A `#` row number column is added first when `ENABLE_ROWNUM` is `TRUE`.

#### `flags/<entity>.php` — list flags

Returns a space-delimited string of flags for the list, e.g.:

```php
function getFlags_permission($objConn_a, $strEntityCode_a, $strFormEntityCode_a)
{
    return "multiselect";
}
```

#### `forms/<formcode>.json` — form layouts

The form layout JSON (see [Metadata-driven forms](#metadata-driven-forms)). Used when `FETCHSYSTEMFORMSFROMFILE` is `TRUE`, and written out from the database by the *build file JSON* operations on System Forms. Forms can also carry optional injected JSON.

#### `dataaccess/<entity>.php` — data accessors

Generated by the *Create data accessor* operation on an entity (one entity or all of them). They give your own PHP a simple way to read and write an entity's table without going through the web service (see [Data accessors](#data-accessors-the-orm) for why they are plain functions and not classes):

| Function | Does |
|---|---|
| `add_<entity>($objConn, $arrFormFields, $arrDBFields)` | Add a record, starting from the entity's blank form |
| `addbulk_<entity>(..., $arrJSONData)` | Same, but you pass the blank form in, so you fetch it once for a bulk load |
| `update_<entity>($objConn, $arrFormFields, $arrDBFields, $strID, $varCustomWhere)` | Update. Only updates the JSON and exposed columns when called with an ID |
| `delete_<entity>` / `deleteExtension_<entity>` | Delete from the main or extension table |
| `fetch_<entity>($objConn, $strDBFields, $strID, $varCustomWhere, $strOrderBy)` | Open a recordset (`id`, `jsondata` and any columns you ask for) |
| `fetchValue_<entity>($objConn, $strFieldName, $strID, $varCustomWhere)` | Read one value |
| `getFormTemplate_<entity>($objConn)` | The entity's blank form |

- Form fields: `[ fieldname, value, valuedescription ]`. The field is looked up in the form's main data section; use `SECTIONCODE.FIELDNAME` for another section.
- DB fields and custom where: `[ type, column, value ]`, where type is `n` / `nn` (number / nullable number), `s` / `sn` (string / nullable string) or `d` / `dn` (date / nullable date).
- The custom where can also be a plain SQL string. With no where and no ID, nothing matches (`1 = 0`), so a missing ID can't update or delete the whole table.
- Data accessors write straight to the table. They don't run the `beforeafter` handlers and don't write a history row.

### Custom operations

Entity operations (the buttons on a list) can run server-side code through `actionEntityCustomOperationExecute`:

- An operation code starting with `INVOKE_` calls the function `invoke_...`
- Any other operation code calls `<entity>_<operation>`
- The function must be in the whitelist returned by `getCustomActions()` in `ws/modules/entity/inc-customactions.php`, or it won't run. Each entry names the file to load from `ws/modules/entity/actions/` and the function to call.

Built-in custom operations include:

| Operation | Does |
|---|---|
| `addto_<parent>__<child>` | *Add to* links, e.g. permissions to a profile, profiles to a user, users to a branch, participants to a roster template, modules to a system |
| Login As / My Client Login As | Log in as another user or client (return with `public_return`) |
| Reset password / permission / document repository | Admin resets |
| Build / publish / run / test application | Development module applications |
| Create data accessor(s) | Generate data accessors for one or all entities |
| Build file JSON (one / all) | Write form layouts to `ws/modules/entity/forms/` |
| Touch system form | Re-save a form layout |
| System build / rebuild | System builds |
| Test / create task for outbound integration | Integrations |
| Send email status update | Email |
| Create roster from template | Projly rosters |

### Code convention: matching functions

Functions that do the same job for different kinds of data — e.g. `actionEntityDataAdd`, `actionFormAdd` and `actionFormDataAdd` — are kept **line for line the same**. Each step is marked with a letter (`// A:` ... `// A:end`) and blank lines are used so the same step sits on the same line number in each file. If you change one, change all of them and keep the lines matching.

---

## Data access

### `database.php`

About 90% of data access goes straight through `ws/modules/utils/database.php`, a thin layer over PDO (MySQL driver).

| Function | Does |
|---|---|
| `dbOpen` / `dbClose` | Open and close a connection |
| `dbOpenRecordset` / `dbReadRecord` / `dbCloseRecordset` | Run a query and walk the rows |
| `dbReadValue` / `dbReadValueByID` | Read a single value (the query returns it as `returnvalue`) |
| `dbExecuteSQL` | Run an insert, update, delete or DDL statement |
| `dbPrepareSQL`, `dbBindS` / `dbBindI` / `dbBindB`, `dbExecuteST` | Prepared statements with bound string, integer and blob parameters |
| `dbBeginTrans` / `dbEndTrans` | Transactions (see below) |
| `dbLastInsertID` | The ID of the last insert |
| `dbBuildWhere` / `dbBuildOrderBy` | Build `WHERE` and `ORDER BY` from a filter or sort list, using only whitelisted fields |
| `dbGetIDFromCode` / `dbGetCodeFromID` / `dbGetDescriptionFromID` | Common lookups |
| `dbCounterCreate` / `dbCounterGetNextValue` | Counters |
| `dbExport` / `dbImport` / `dbExportToCSV` / `dbExportToHTML` | Export and import (used by the `/config` tool) |
| `systemSettingGet` / `systemSettingPut` | System settings in `c_system` (schema version, server disabled, licence key) |

**Writing SQL.** SQL is written as a template with `~PLACEHOLDERS~`, and each value is swapped in with an escaping helper:

```php
$strSQL = "select id, jsondata from ~TABLENAME~ where client_id = ~CLIENTID~ and code = '~CODE~'";
$strSQL = str_replace('~TABLENAME~', ff(getTableNameEntity('project', false)), $strSQL);
$strSQL = str_replace('~CLIENTID~', ff($strClientID), $strSQL);
$strSQL = str_replace('~CODE~', ff($strCode), $strSQL);
$objResult = dbOpenRecordset($objConn, $strSQL, __FUNCTION__);
```

| Helper | Use for |
|---|---|
| `ff()` | Any value (escapes `\` and `'`) |
| `ffn()` | A value that can be null (returns `null` when empty) |
| `ffel()` / `ffeu()` | Entity and table names — strips everything except name characters, then lower / upper cases |

`getTableNameEntity('<entity>', false)` gives the live table name and `getTableNameEntity('<entity>', true)` gives the history table name.

**Transactions nest.** `dbBeginTrans` / `dbEndTrans` keep a nesting count. Only the outermost pair actually starts and commits. If anything fails at any level, the whole transaction rolls back when the outermost `dbEndTrans` runs. So functions can each wrap their own work in a transaction and still be safely called from inside a bigger one. Autocommit is turned off, and the isolation level comes from `DBSYSTEM_ISOLATIONLEVEL` (default `READ UNCOMMITTED`).

### Data accessors (the "ORM")

It's an ORM, but not in the usual sense. It maps a table and its form fields to a set of plain functions — `add_<entity>`, `update_<entity>`, `fetch_<entity>`, `fetchValue_<entity>`, `delete_<entity>` and so on (see [`dataaccess/`](#dataaccessentityphp--data-accessors)). There are no business-object classes wrapping the data.

That's on purpose:

- The tables are already shaped around the business objects, so the rows and recordsets **are** the data and collections you need. Wrapping them in classes adds nothing.
- Masses of classes don't perform. This design is proven serving 20 users from a 256 MB device.

The accessors are generated, not hand-written. `ws/modules/entity/dataaccess/template.php` is the template: the *Create data accessor* operation replaces `%%ENTITY_LCASE%%`, `%%ENTITY_UCASE%%` and `%%ENTITY_GUID%%` (the form's main data section) and writes out `ws/modules/entity/dataaccess/<entity>.php`. Change the template and regenerate to change every accessor.

Load an accessor like any other code: `dependencies('entity/dataaccess/document')`.

### Documents: the first data accessor user

The document module (`docs`) was the first code converted to data accessors, to prove they simplify code. It's also the best example to read.

**Compound documents.** A document can be made of several files (e.g. an HTML page and its images). Each document gets a base filename (`<GUID>-<documentid>`), and every file belonging to it is stored in the cluster as `<filenamebase>-<filename>`. Image thumbnails sit alongside as `<name>-thumb.<ext>`.

| Function | Does |
|---|---|
| `documentAdd` | Create the document record and allocate its base filename. Tags are made from the description |
| `documentComponentAdd` / `documentComponentCopy` | Add a file to the document, from a variable or by copying an existing file. Updates storage used on the document and its repository |
| `documentCommit` | Mark the document committed and update the repository's document count |
| `documentSimpleAdd` / `documentSimpleCopy` | All three steps in one call, for single-file documents |
| `documentOpen` | Reopen a committed document so its files can be added to or replaced, then commit again |
| `documentUpdate` | Update notes and tags |
| `documentDownload` | Send the document to the browser. For a multi-file document it sends the **last** file: the first file should be the rawest, the last the most viewable |
| `documentClusterSave` / `Copy` / `Load` / `Exists` / `Download` | Low-level file operations on the cluster |

Web service functions (`ws/modules/docs/inc-entry.php`):

| Function | Does |
|---|---|
| `docs_documentdownload` | Download a document (`id`, `download`) |
| `docs_fetchimage` | Fetch an image (`id`, `download`) |
| `docs_imagethumbsfetchnext` | Page through image thumbnails, newest first (`fetchcount`, `lastfetched`, `filter` on code, description, tags and filename) |

All three need `VW_DOCUMENT`.

---

## Frontend (AWAFOS)

The browser side is **AWAFOS** (`app/inc-os.js`): a small windowed operating system that runs in the browser. It handles:

- forms as windows (MDI or docked into desktop regions), with a taskbar, menu, zoom and a mobile mode
- a message bus: `os.broadcast(formID, queue, message, data)` reaches every open form, and other browser tabs of the same session too (`multimon` capability)
- lazy loading of forms, form logic and third-party code, so only what is used gets downloaded
- per-browser capability detection (canvas, touch, printing, speech, geolocation, and so on), so features switch themselves on and off by device
- hash routing: `#!module.frmFormName` opens a form
- keyboard shortcuts, timers, server events, speech and printer providers
- web service calls (`os.ajaxRequestCreate` / `os.ajaxCall`) with the request format described under [Web service](#web-service)

### Forms

A form is two files in `app/modules/<module>/`:

| File | Holds |
|---|---|
| `frmName.htm` | The HTML template |
| `frmName.js` | `function <module>_frmName(os, strFormID, objParameters)` |

Open one with `os.showForm('module.frmName', { ...parameters })`, or `os.showFormPopup(...)` for a modal that returns a result. The OS calls the form's event functions:

`Form_onLoad` `Form_onFocus` `Form_onBroadcast` `Form_onResize` `Form_onClick` `Form_isDirty` `Form_canClose` `Form_allowMultipleInstances` `Form_getDesktopRegion` `Form_onPermissionCheck`

### Low-code forms need no code

Almost every low-code form goes through the generic forms in `app/modules/entity/`. No form-specific code is needed:

| Form | Does |
|---|---|
| `entity.frmLister` | List with search, paging, sorting and the entity's operation buttons |
| `entity.frmForm` | Add, edit and view a record. Save, Save & Close, Save & New, and **Previous / Next version** buttons (the time machine, for users with `HIST_DATAFORM`) |
| `entity.frmHTMLForm` | Read-only HTML view of a form, with version stepping and print. Used for CMS pages such as *About* |
| `entity.frmEntityChooser` | Popup picker: pick one or more records from an entity and return them to the calling form |

Menu tiles open these with parameters, e.g.:

```js
os.showForm('entity.frmLister', {
    type: 'form', entity: 'systemform', formentity: 'PROJECTISSUE', formcode: 'PROJECTISSUE',
    mode: 'renderer', title: 'Project Bugs',
    fixedfilter: [{ field: 'ffa99b8231_dfd1_424f_be14_c9052b2bdbe6_projectissuetype', value: 'Bug' }]
});
```

`entity.frmForm` also takes `defaults` (a list of `section`, `field`, `value`, `description`) to pre-fill fields on a new record.

### Form-specific logic: `app/modules/entitylogic/`

When one form needs its own behaviour, put it in `app/modules/entitylogic/<entity>.js`. It's lazy loaded with the entity's form, and only if the file exists.

```js
function entityform_server(os, strFormID, objParameters)
{
    var m_objThis = this;
    var FIELD_FILESYSTEMPROTOCOL = os.massageClassName('gafb65c33-7df8-4b01-a919-4df125c08f0e', 'FILESYSTEMPROTOCOL');

    // called once the form has rendered
    this.onRegister = function (objParentForm, objFormRenderer)
    {
        os.bindEvent(m_objThis, strFormID, '.' + FIELD_FILESYSTEMPROTOCOL, 'FileSystemProtocol', 'onClick');
        showProtocol();
    };

    this.FileSystemProtocol_onClick = function () { showProtocol(); };

    function showProtocol() { /* show or hide fields based on the selected protocol */ }
}
```

- `os.massageClassName(sectionGUID, FIELDNAME)` gives the CSS class of a rendered field, so you can find it with `os.element(strFormID, '.' + className)`.
- `Renderer_onEvent(type, formID, locator, eventName)` receives field events from the form renderer (`onChange`, `onClick`, `onClear`, `onFocus`, `onLoad`).

### UI components (`app/inc-osutils-*.js`)

| Component | Does |
|---|---|
| `jDock` | Menus and toolbars built from a map of tiles. Hides tiles the user has no permission for, and moves overflow into a *More…* dropdown |
| `jFormRenderer` | Renders a form layout's JSON into a working form, validates it and returns the data |
| `jDatatableRenderer` | Server-side paged grid (DataTables) used by the listers |
| `jGrid` | Editable grid (SlickGrid) |
| `jEditor` | Rich text editor (TinyMCE) |
| `jCodeEditor` | Code editor (Ace) |
| `jCanvas` | Drawing / signature capture, output as PNG or JPEG |
| `suburbFinder`, `abnFinder`, `predictiveTextFinder` | Lookup-as-you-type helpers |

The full list of field types the renderer supports is under [Metadata-driven forms](#metadata-driven-forms).

### Code naming

Variables carry their type as a prefix: `str`, `int`, `flt`, `bln`, `arr`, `obj`, `cb` (callback). Members are `m_`, globals are `g_`, and function arguments end in `_a` (e.g. `strFormID_a`). The same convention is used in the PHP.

---

## Building the JavaScript

The app runs from the original `.js` files or from built `.z.js` / `.z.htm` files, which are linted, minified, obfuscated and run through Closure Compiler. The loader tries the `.z.` file first and falls back to the original. Set `debug_source` to `TRUE` in the environment file to always load the originals.

| Step | Run |
|---|---|
| Build the third-party bundles | `app/bundle/_tools/createbundles-runme.bat` |
| Delete old built files | `_resources/tools/deleteoriginaljs-runme.xbat` |
| Lint (JavaScript Lint, `jsl`) | `_resources/tools/jsl-runme.bat` |
| Minify, obfuscate and compile to `.z.` files | `_resources/tools/obfuscateminify-runme.bat` |

Each JS file starts with `/*jsl:option explicit*/` and `/*jsl:import ...*/` lines for the linter.

---

## Web service

### Entry points

The PHP side has only three entry points:

| File | Used for |
|---|---|
| `ws/server.php` | **Everything.** All JSON web service calls go here |
| `ws/upload.php` | File uploads (saved to `ws/temp/uploads/`) |
| `ws/public.php` | Public services that don't need a login (currently barcode generation) |

If you build a native iOS or Android app, or anything else that talks to AWAF, talk to `ws/server.php`.

### How it stays small

`ws/modules/system/webService.php` is the main server class. Nothing is loaded up front: each module has an `inc-entry.php` that dispatches its own functions, and each function pulls in only the files it needs, when it needs them (`dependencies('module/file,...')`). A request only loads the code for that one call.

AWAF was designed this way over 10 years ago with one target: serve 20 users from a small Android media box with 256 MB of RAM. It does.

### Request format

POST a JSON body to `ws/server.php`:

```json
{
  "AWAFOS": {
    "securitytoken": "<token returned by public_login>",
    "clientversion": "1.1.1-BETA.1",
    "deviceidcookie": "<device id>",
    "callerid": "<caller id>",
    "dataid": "<your own id, returned with the response>",
    "function": "integration_taskcreate",
    "parameters": [
      { "name": "outbound", "value": "AWAFAPI-XMIT-GENDER" }
    ]
  }
}
```

- Function names are `module_action`, e.g. `public_login`, `core_servereventscheck`, `integration_taskcreate`.
- `parameters` is always a list of `name` / `value` pairs.
- JSONP is supported: send the same JSON as a `json` GET parameter along with `callback`.

**Functions that work without a login:**
`public_login` `public_logout` `public_return` `public_authenticationcheck` `public_register` `public_registrationfetch` `public_confirmation` `public_verification` `public_passwordreset` `public_documentdownload` `public_fetchimage` `public_fetchmetadata` `public_fetchwiki` `public_getdatabasename`

Every other function needs a `securitytoken` that matches the session. If it doesn't match, the session is cleared and the caller is logged out.

**GET shortcuts** (with a `token`):

| Parameters | Does |
|---|---|
| `token` + `image` (+ `download`) | Fetch an image |
| `token` + `document` (+ `download`) | Download a document from a repository |
| `token` + `wiki` | Fetch an online help page |
| `metadata` | Fetch metadata |

### Native iOS and Android apps

- Put `(ANDROID)` or `(IOS)` in `clientversion`. The server then uses your `securitytoken` as the session ID, so the app doesn't need cookies.
- Send a `devicetoken` parameter to register the device for push notifications.
- A failed security check returns an empty result to a native app instead of redirecting to `logout.php`.
- The `public_...` functions stay in the `public` module on purpose, because existing native apps depend on them being there.

### Session handler

There are three session handler files in `ws/modules/system/`:

| File | For |
|---|---|
| `sessions.php` | The one that gets loaded |
| `sessions-php5.php` | PHP 5 |
| `sessions-php7.php` | PHP 7 |

Rename the one for your PHP version to `sessions.php`.

---

## Folder layout

| Folder | Holds |
|---|---|
| `app/` | The JavaScript application |
| `app/modules/` | JavaScript modules (`<module>/frmName.js` + `.htm`) |
| `app/modules/entity/` | The generic low-code forms (lister, form, HTML form, chooser) |
| `app/modules/entitylogic/` | Optional per-entity form logic, lazy loaded |
| `app/bundle/` | Third-party bundles and the bundle build tool |
| `_resources/tools/` | Lint and minify tools |
| `ws/` | The PHP web services (`server.php`, `upload.php`, `public.php`) |
| `ws/modules/` | PHP modules (`system/webService.php` is the main server class) |
| `ws/modules/import/fileformats/` | Import file formats |
| `ws/temp/` | Temporary files (`pending/`, `uploads/`, `general/`) |
| `ws/log/` | Log files |
| `repository-system/` | System document repository |
| `repository-client/` | Client document repository |
| `config/` | The `/config` tool, its `config.json` script, templates and environment files |
| `builds/` | System builds |
| `output/` | Generated output files |
| `wiki/` | Online help |

---

## Feature groups

What appears in each menu group (subject to permissions):

| Group | From | Contents |
|---|---|---|
| **Menu** | AWAF | Home, My Messages, View Cart, Todo List, Work Queue, Reminders, Contacts, Online Help (wiki) |
| **Projly** | Projly | Dashboard, all issue lists, tasks, activities, rosters, projects, sprints, R&D, billing/cost categories, campaigns, participants, resources, stakeholders |
| **Configuration - Projly** | Projly | All the Projly lookup lists |
| **Licence Manager** | AWAF | Software licences (version, licence type, download site, audit date, auditor, audit notes) and licence types |
| **Printing** | AWAF | Print jobs, archived print jobs, my printers, public printers, private and public print queues |
| **Import** | AWAF | Imports, import documents, import images |
| **Documents** | AWAF | Documents, repositories (with storage use and retention types), videos and video categories |
| **Integration** | AWAF | Inbound and outbound integrations and tasks, servers (FTP/HTTP etc. with proxy settings), API calls, API call types, API keys, file systems, file types |
| **Settings** | AWAF | My devices, my form devices, password, user preferences, my user |
| **My Account** | AWAF | About, account, client settings, despatch preferences, my products, receipts, payments, transactions, logout |
| **Configuration - System** | AWAF | Licensing, CMS pages, colours, contact types and statuses, desktop regions, document types and retention, FAQs, genders, honorifics, icons, industry types, message folders and templates, negotiated rates and discounts, payment methods and statuses, printer types and purposes, products and product types, registration types, sequences, server protocols, startup items, states, suburbs, todo / transaction / user / work queue statuses, video sources |
| **Security** | AWAF | Security dashboard, accounts, branches, clients, data audit log, devices, device log, profiles, registrations, password resets, users |
| **System** | AWAF | Application health, batch jobs, config processes, database processes, schema changes (pending / done), system flag values |
| **Development** | AWAF | Applications and modules (JS logic with test documents) |
| **Developer** | AWAF | Systems, system modules, system builds, nav items, entities, entity operations, permissions, published forms, mobile forms, form / report layouts and builders, section templates, fragments, data types, authentication / chart / integration plugins and types, charts, display orders, map providers, check digit types, sequence types, system flag types, themes |

### Remote printing

AWAF can print from anywhere to a printer attached to any computer, including printing from a phone, over the internet.

- A printer is registered against the computer (device) it's attached to. Printers can be private or public, and grouped into print queues (*Printing* menu: My Printers, Public Printers, My Private / Public Printer Queues).
- A phone or any other device creates a **print job** and puts it on a queue.
- The **event bus** (pub/sub, the `c_esb` tables with broadcasters and listeners on event queues) tells the computer that owns the printer that there's a job waiting.
- That computer prints it locally through [QZ Tray](https://qz.io/), including to thermal printers, and the job goes to the archived print jobs (*Print Jobs (Archived)*).
- Each device has its own printer and print queue settings, and users can set a device per form (*My Form Devices*).

All the QZ Tray support code is included, so this is a working example of how to build remote printing into a web app. QZ Tray itself is commercial and isn't included; see [Third-party components](#third-party-components).

Other AWAF pieces:
- **Work queue** with item types, statuses, status trigger rules and assignment
- **Messaging:** internal messages with folders, message templates, email via PHP mail, PHPMailer or SendGrid, with a prepared-message queue and retries
- **Event bus:** pub/sub broadcaster / listener event queues (`c_esb`), used for things like [remote printing](#remote-printing)
- **Two-factor authentication** (Google Authenticator)
- **Sequences** for numbering, with prefix, suffix, length and check digits
- **Batch jobs** with scheduling, priority and progress
- **Device tracking:** each device's IP, user agent and capabilities, plus per-user, per-form device selection (e.g. which printer)
- **Mobile mode** with its own forms

---

## Configuration tool (/config)

This is part of AWAF. Browse to `/config` to open the configuration tool. It runs scripts written in the **xBackup** script format (`config.json`). A script is made of:

- **optionSets** — the menu groups shown in the tool (flagged `installed`, `developer` or `advanced` to control when they show)
- **options** — the individual choices in each group, each running a list of task sets
- **taskSets** — named lists of tasks

Built-in option sets:

| Code | Group |
|---|---|
| `BU` | Backing up (to the Backups folder) |
| `UTD` | UAT - typical deploy |
| `AD` | Advanced - deleting |
| `AI` | Advanced - installation (from the Updates folder) |
| `AR` | Advanced - restoring (from the Backups folder) |
| `CP` | CRONable processes (backups, temp file purge) |
| `DPP` | Developer - production packaging (to the Updates folder) |
| `DS` | Developer - database scripts (purge clients, history, temp) |
| `BT` | Batch tasks (install CRONable options) |
| `DDU` | Developer - development utilities |

Task commands available to scripts:

`initialise` `set` `alert` `copyFile` `copyFolder` `deleteFile` `deleteFolder` `deleteFolderContent` `createFolder` `archiveFolder` `unarchive` `createDatabase` `deleteDatabase` `exportData` `importData` `executeSQL` `createMetaData` `configureApplication` `installOption`

Variables use `%NAME%`. System values are provided as `%SYS_...%` (for example `%SYS_TODAY%`, `%SYS_YYYYMMDDHHNNSS%`, `%SYS_PATH_CONFIG%`, database hostnames and names). The `pre` task set sets up the common variables and `post` cleans up the temp folder.

Example — back up the system databases:

```json
{
  "exec": "exportData",
  "description": "main database schema",
  "database": {
    "hostname": "%DBSYSTEMMAIN_HOSTNAME%",
    "dbname": "%DBSYSTEMMAIN_DATABASENAME%",
    "login": "%DBSYSTEMMAIN_LOGIN%",
    "password": "%DBSYSTEMMAIN_PASSWORD%"
  },
  "target": "%PATH_TEMP%/database-system/schema.sql",
  "chunksize": 1000,
  "flags": ["schema"]
}
```

You can write your own option sets and task sets in the same format.

---

## Environment configuration files

Each environment (DEV, UAT, PROD) has a JSON file in the config folder. When you pick one in `/config`, the `configureApplication` task fills the PHP templates with its values and copies them into place:

| Template | Destination | Holds |
|---|---|---|
| `template/inc-app-projlyclient.php` | `inc-app-projlyclient.php` | App name, social links, client modules |
| `template/inc-constants.php` | `inc-constants.php` | Frontend constants |
| `template/inc-env.php` | `inc-env.php` | App code, paths, developer / debug switches |
| `template/inc-settings.php` | `inc-settings.php` | Registration, PayPal, credit card provider |
| `template/ws/inc-app-projlyclient.php` | `ws/inc-app-projlyclient.php` | App name, product ID, licence warning days, new-client admin login / password / profile, default profiles |
| `template/ws/inc-constants.php` | `ws/inc-constants.php` | Server modules, paths, repository locations, log files, encryption plugins, secure IDs, shared reference data |
| `template/ws/inc-env.php` | `ws/inc-env.php` | Versions, paths, URLs, system owner client, payment gateway settings, 2FA, client databases switch |
| `template/ws/inc-settings.php` | `ws/inc-settings.php` | Registration, timezone, date format, LDAP, email, batch and email processing, special client codes, debugging |
| `template/ws/inc-dbsettings-client.php` | `ws/inc-dbsettings-client.php` | Client main / history / temp database connections |
| `template/ws/inc-dbsettings-system.php` | `ws/inc-dbsettings-system.php` | System main / history / temp database connections |

Shape of an environment file:

```json
{
  "code": "projlyclient-development-projlyclient-dev",
  "description": "Projly Client Development (localhost/projly-dev)",
  "files": [
    {
      "source": "template/inc-env.php",
      "destination": "inc-env.php",
      "settings": {
        "app_code": "projlyclient",
        "app_home": "/projly-dev",
        "app_domain_path": "http://localhost/projly-dev/",
        "developer": "TRUE",
        "debug_js": "TRUE"
      }
    }
  ]
}
```

Each setting in the JSON matches a PHP constant in the target file, with the name in upper case. For example, this in the environment file:

```json
{
  "source": "template/ws/inc-dbsettings-system.php",
  "destination": "ws/inc-dbsettings-system.php",
  "settings": {
    "dbsystemmain_hostname": "localhost",
    "dbsystemmain_login": "root",
    "dbsystemmain_password": "passw0rd",
    "dbsystemmain_databasename": "projly_systemmain"
  }
}
```

ends up in `ws/inc-dbsettings-system.php` as:

```php
define('DBSYSTEMMAIN_HOSTNAME', 'localhost');
define('DBSYSTEMMAIN_LOGIN', 'root');
define('DBSYSTEMMAIN_PASSWORD', 'passw0rd');
define('DBSYSTEMMAIN_DATABASENAME', 'projly_systemmain');
```

Anything not in the environment file keeps the value from the template. That covers things like the encryption plugins, timezone, date format, LDAP and batch settings. To change one of those per environment, add it to the environment file's `settings`.

---

## Modules

**Client (JavaScript) modules:**
`core` `dash` `entity` `leaflet` `widgetclock` `widgetdesktopsizer` `widgetdock` `widgetformbuilder` `widgetspeech` `widgetterm` `widgettube`

**Server (PHP) modules:**
`cart` `core` `dash` `developer` `docs` `entity` `esb` `import` `integration` `msg` `process` `projly` `public` `report` `security` `setting` `system` `utils`

`projly` is the Projly application module. The rest are AWAF.

An AWAF application is added by listing its modules in the environment file (`clientmodules` and `servermodules`) and adding its menu groups to the nav maps.

---

## Requirements

- PHP 5.4+ or PHP 7 (PHP 5.4 support is kept on purpose)
- Browsers: ES5 JavaScript, kept on purpose for the widest support
- MySQL with InnoDB
- A web server that runs PHP (Apache with `.htaccess` is what the install scripts expect)

---

## Installation

1. Copy the application to your web server.
2. Rename the session handler for your PHP version to `ws/modules/system/sessions.php` (see [Session handler](#session-handler)).
3. Create an environment file for your server in the config folder (copy the dev example and change paths, URLs and database logins).
4. Browse to `/config`.
5. Run **UAT - Typical Deploy → Full Install** (or the matching *Advanced - Installation* steps). This creates the main, history and temp databases, imports the schema and data, installs the repository and applies your chosen configuration.
6. Log in to each built-in tenant (see [The built-in tenant areas](#the-built-in-tenant-areas)) and change the default passwords.
7. Log in as `owner` / `admin` and set up products. Set up the profiles new clients should get in the `default` tenant.

---

## Roadmap

**PHP 5.4 and ES5 stay supported for the life of the project**, so it runs on as many servers and browsers as possible.

If the product continues, the plan is:

- **Move SQL to the data accessors.** Replace most of the hand-written SQL with the generated [data accessors](#data-accessors-the-orm), the same way the document module was converted.
- **Swap in the Cyborg Suite components.** Replace the lister, grids, form renderer, form editor and text editor with the Cyborg Suite versions. These are already open source (MIT) and usable: [GitHub](https://github.com/PrimalNinja/cyborgdesigner) · [cyborgunicorn.com.au](https://cyborgunicorn.com.au/)

  | Current | Replacement | Demo |
  |---|---|---|
  | Lister and grids (`jDatatableRenderer`, `jGrid`) | **List Renderer** — list and gallery views, paged / infinite / expand, multi-column sort, live search, Excel-like inline editing with add-new-row | [Demo](https://cyborgunicorn.com.au/cyborgdesigner/listrenderer/listRendererDemo.html) · [Editing demo](https://cyborgunicorn.com.au/cyborgdesigner/listrenderer/listRendererEditingDemo.html) |
  | Form renderer (`jFormRenderer`) | **Form Renderer** — renders the JSON form definition at runtime, built-in field types plus your own registered renderers, validation and data read-back | [Demo](https://cyborgunicorn.com.au/cyborgdesigner/formrenderer/formRendererDemo.html) |
  | Form Builder | **Cyborg Designer** — drag-and-drop visual IDE with tabs for Application, Dashboard, Database Schema, Form, Menu, Report and AI Orchestration. Unlimited field types, JSON output that drives the Form and List Renderers | [Designer](https://cyborgunicorn.com.au/cyborgdesigner/formdesigner/index.html) · [Wiki](https://cyborgunicorn.com.au/cyborgdesigner/formdesigner/wiki/index.php) |
  | Text and code editors (`jEditor`, `jCodeEditor`) | **Text Editor** — textarea, input, rich, code and readonly modes, plus a JSON editor with validation | [Demo](https://cyborgunicorn.com.au/cyborgdesigner/texteditor/textEditorDemo.html) |

- **Charts and graphs.** Projly already collects everything a project tool needs — estimates vs actuals, costs, billing rates, sprints, statuses, activity logs, rosters. The chart plugin framework and dashboards are in place; the useful graphs (burndown, velocity, cost vs budget, issue trends) are still to be built.

---

## Third-party components

These are the components recorded in the application's own **Licence Manager** (*Licence Manager → Software Licences*), grouped by licence. "—" means no version was recorded.

**MIT License**

| Component | Version | Author |
|---|---|---|
| [barcode](https://www.kreativekorp.com/software/) | — | Kreative Software |
| [Bootstrap](https://getbootstrap.com/) | 3.3.4 | Twitter Inc |
| [bootstrap-limit.js](https://github.com/trongrg) | 0.1.0 | TrongTran and contributors |
| [canvas-to-blob.js](https://github.com/blueimp) | — | Sebastian Tschan |
| [Chart.js](https://www.chartjs.org) | 4.4.1 | — |
| [DataTables](https://datatables.net/) | — | SpryMedia Ltd |
| [html2canvas](http://html2canvas.hertzen.com/) | 0.5.0-alpha | Niklas von Hertzen |
| [i18n](https://jquery.com/) | — | jQuery Foundation and contributors |
| [intro.js](https://introjs.com/) | 0.9.0 | Afshin Mehrabani |
| [jQuery](https://jquery.com/) | 1.11.0 | the jQuery Project |
| [jQuery UI](https://jquery.com/) | 1.10.3 | the jQuery Project |
| [jQuery blockUI](http://malsup.com/jquery/) | — | M Alsup |
| [jQuery caret](https://github.com/lukemorton) | — | Luke Morton |
| [jQuery File Upload](https://blueimp.github.io/jQuery-File-Upload/) | 5.32.1 | Sebastian Tschan |
| [jquery.fileDownload](http://johnculviner.com/) | 1.4.2 | John Culviner |
| [jQuery Form](http://malsup.com/jquery/form/) | 3.09 | M Alsup |
| [jQuery formBuilder](http://kevinchappell.github.io/formBuilder/) | 1.10.3 | Kevin Chappell |
| [jQuery Hashchange](https://github.com/cowboy) | — | Ben Alman |
| jQuery Idle Timer | 1.0 | Nicholas C. Zakas, Paul Irish, Mike Sher |
| [jQuery Lazy Load](https://github.com/tuupola) | 1.9.3 | Mika Tuupola |
| [jQuery Terminal Emulator](https://terminal.jcubic.pl/) | — | Jakub Jankiewicz |
| [jQuery Timer](http://jchavannes.com/jquery-timer) | — | Jason Chavannes |
| [jQuery UI Maps](https://github.com/stevewithington/jquery-ui-map) | 3 | Johan Säll Larsson |
| [jsdiff.js](http://ejohn.org/projects/javascript-diff-algorithm/) | — | John Resig |
| [normalize.css](https://openseattle.org/components/normalize-css/) | 1 | Nicolas Gallagher, Jonathan Neal |
| [PhpConsole](https://github.com/barbushin) | — | Barbushin Sergey |
| [phpQuery](http://code.google.com/p/phpquery/) | — | Tobiasz Cudnik |
| [qTip2](http://qtip2.com) | 2.2.0 | Craig Thompson |
| [Raphaël](https://dmitrybaranovskiy.github.io/raphael/) | 2.1.2 | Dmitry Baranovskiy |
| [Respond.js](https://github.com/scottjehl) | — | Scott Jehl |
| [SlickGrid](https://github.com/6pac/SlickGrid/wiki) | 2.1 | Michael Leibman |
| [jQuery UI Touch Punch](https://www.nuget.org/packages/jQuery-UI.Touch-Punch/) | — | David Furfero |
| [Tubular](http://www.seanmccambridge.com/tubular/) | 1.0 | Sean McCambridge |
| [x2js](https://github.com/abdolence) | — | Abdulla Abdurakhmanov |

**BSD 3-Clause License**

| Component | Version | Author |
|---|---|---|
| [Ace Code Editor](https://ace.c9.io/) | — | — |
| [d3-cloud.js](https://github.com/jasondavies) | — | Jason Davies |
| [shortcut.js](http://www.openjs.com/scripts/events/keyboard_shortcuts) | 2.01.B | Binny V A |

**Apache License 2.0**

| Component | Version | Author |
|---|---|---|
| Array2XML & XML2Array | — | Lalit Patel |
| JSBase64 | 1.0 | Vassilis Petroulias |

**GNU LGPL**

| Component | Version | Licence | Author |
|---|---|---|---|
| [adLDAP](http://adldap.sourceforge.net/) | 4.0.4r2 | LGPL-3.0 | Scott Barnett, Richard Hyland |
| [html2pdf](https://www.yaronet.com/forums/543-html2pdf) | — | LGPL-3.0 | Laurent Minguet |
| [phpqrcode](http://phpqrcode.sourceforge.net/) | 1.1.4 | LGPL-3.0 | — |
| [PHPMailer](https://phpmailer.github.io/PHPMailer/) | — | LGPL-2.1 | Jim Jagielski |
| [TinyMCE](https://www.tiny.cloud/) | 4.0.3 | LGPL-2.1 | tinymce.com |

**Other licences**

| Component | Licence | Author |
|---|---|---|
| [json2.js](https://github.com/douglascrockford) | Public Domain | Douglas Crockford |
| [jquery.scrollstop](https://github.com/ssorallen) | Unlicense | Ross Allen |
| Menu icon ([The Noun Project](https://thenounproject.com/)) | Creative Commons 3.0 | Marek Polakovič |
| [ABNLookup](https://github.com/Kwozzie) | Own licence | Justin Swan |
| BrowserDetect | Own licence | — |
| [clock.js](https://github.com/jakobwesthoff) | Own licence | Jakob Westhoff |
| [eWAY GatewayConnector](https://www.eway.com.au/) | Own licence | Web Active Corporation Pty Ltd |
| [jQuery Input Limiter](http://rustyjeans.com/jquery-plugins/input-limiter/) | 1.3.1, own licence | Russel Fones |
| JPEGEncoder | Own licence | Adobe Systems Incorporated |

Each component keeps its own licence. The full licence texts are stored under *Licence Manager → Software Licence Types*.

**Not included: [QZ Tray](https://qz.io/).** Direct printing to local printers (including thermal printers) uses QZ Tray, which is commercial and is not part of this repository. To use it, get your own QZ Tray licence and put its `qz-tray.js` in `app/js/`. The loader already looks for it there. All of AWAF's QZ Tray support code is included — see [Remote printing](#remote-printing).

---

## License

TBA

---

## Comparison

How AWAF compares with other application platforms. ✓ = has it, ✗ = doesn't. **Bold** features are ones where AWAF stands out.


**Core Platform**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 1 | Multi-tenancy | ✓ | ✓ | ✗ | ✓ | ✓ | ✓ | ✗ | ✗ | ✗ | Each company as tenant with own user security management |
| 2 | User authentication & authorization | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | All enterprise platforms have built-in auth |
| 3 | Role-based access control | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |
| 4 | Security & encryption | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |

**Data Management**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 5 | Custom objects/entities | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ | WordPress uses custom post types |
| 6 | Database schema management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ | Full schema management built-in |
| 7 | Data import/export | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |
| 8 | Data backup/recovery | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |
| 9 | Duplicate management | ✗ | ✓ | ✗ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |
| 10 | Audit trails | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ | WordPress needs plugins |
| 11 | **Data history traversal** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Navigate prev/next through form history |
| 12 | File storage & management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |
| 13 | Database backend | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | Various: Oracle, MySQL, HANA, Dataverse |
| 14 | **BigData architecture support** | ✓ | ✗ | ✗ | ✗ | ✓ | ✗ | ✗ | ✗ | ✗ | Hybrid BigData/Relational design |
| 15 | Relational database model | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |

**User Interface**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 16 | **MDI (Multiple Document Interface)** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Unlimited windows on unlimited tabs/monitors |
| 17 | SDI (Single Document Interface) | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | Typical web interface |
| 18 | Forms builder | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ | WordPress needs plugins |
| 19 | Custom UI builder | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ |  |
| 20 | Widgets | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ |  |
| 21 | Component system | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | AWAF: lazy loaded |

**Business Logic**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 22 | Workflow automation | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |
| 23 | Process builder | ✗ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ | ✗ |  |
| 24 | Approval processes | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |
| 25 | Validation rules | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ |  |
| 26 | Formula fields/calculations | ✗ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |
| 27 | Batch processing | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |

**Integration & API**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 28 | API (REST/SOAP) | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |
| 29 | Email integration | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |
| 30 | Integration hub | ✗ | ✓ | ✗ | ✓ | ✓ | ✓ | ✗ | ✗ | ✗ |  |

**Reporting & Analytics**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 31 | Reports & dashboards | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ |  |
| 32 | Custom reports builder | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ |  |
| 33 | Search functionality | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ |  |

**Content & Communication**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 34 | Content management | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | WordPress is full CMS |
| 35 | Internal Wiki | ✓ | ✓ | ✗ | ✓ | ✓ | ✓ | ✗ | ✗ | ✗ |  |
| 36 | Video playback | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ |  |
| 37 | Notifications/alerts | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✓ |  |
| 38 | Task management | ✓ | ✓ | ✗ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |
| 39 | Calendar/scheduling | ✓ | ✓ | ✗ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |

**Mobile & Offline**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 40 | Mobile app support | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | ✗ |  |
| 41 | **Native mobile offline data entry** | ✓ | ✗ | ✗ | ✓ | ✗ | ✓ | ✗ | ✗ | ✗ |  |
| 42 | **Offline form & data sync** | ✓ | ✗ | ✗ | ✓ | ✗ | ✓ | ✗ | ✗ | ✗ | Auto-sync when internet returns |

**Advanced Features**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 43 | **Multi-monitor support** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF exclusive |
| 44 | **Multi-device display control** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Control external displays/billboards - AWAF exclusive |
| 45 | **OS-like multitasking** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF exclusive |
| 46 | **Direct to printer printing** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF exclusive |
| 47 | **Remote printing** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF exclusive |
| 48 | **Global mobile printing** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Across sites/world - AWAF exclusive |
| 49 | Applications/modules system | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✗ | WordPress: plugins; Salesforce: apps; AWAF: modules |
| 50 | Custom code | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | AWAF: PHP/JS; Salesforce: Apex; WordPress/DaDaBIK: PHP |
| 51 | **Internal JS development environment** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF exclusive |
| 52 | AI/LLM integration | ✓ | ✓ | ✗ | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | Salesforce: Einstein AI; AWAF: inbuilt (POC demonstrable); Power Platform: Copilot |
| 53 | **Voice recognition** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF: POC demonstrable - exclusive |

**Hosting & Deployment**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 54 | Cloud SaaS hosting | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | Salesforce is SaaS only |
| 55 | **On-premise installation** | ✓ | ✗ | ✓ | ✗ | ✓ | ✗ | ✓ | ✓ | ✓ | User-installable onsite |
| 56 | **WAMP/LAMP/MAMP support** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✓ | ✓ | Runs on standard PHP stacks |
| 57 | **AAMP (Android) support** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Android version of WAMP - exclusive |
| 58 | **Kubernetes deployment** | ✓ | ✗ | ✓ | ✗ | ✓ | ✗ | ✓ | ✓ | ✓ | Containerized deployment |
| 59 | **Azure deployment** | ✓ | ✗ | ✓ | ✓ | ✓ | ✗ | ✓ | ✓ | ✓ | Azure hosting support |
| 60 | **SAAS product creation/configuration** | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Build and configure SAAS products - exclusive |

**UI Frameworks**

| # | Feature | AWAF | Salesforce | Oracle APEX | MS Power | SAP S/4HANA | ServiceNow | Odoo | WordPress | DaDaBIK | Notes |
|---|---|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|:-:|---|
| 61 | jQuery | ✓ | ✗ | ✓ | ✗ | ✗ | ✗ | ✗ | ✓ | ✗ | AWAF uses jQuery |
| 62 | Angular | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Front-end framework - requires external auth/backend |
| 63 | React | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | Front-end framework - requires external auth/backend |
| 64 | Vue | ✓ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | ✗ | AWAF can use Vue but doesn't usually; Front-end framework - requires external auth/backend |