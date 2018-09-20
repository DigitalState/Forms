# Form

The form api endpoints allow authorized users to read, modify and delete forms.

## Table of Contents

- [Get List](#get-list)
- [Get Item](#get-item)
- [Add Item](#add-item)
- [Edit Item](#edit-item)
- [Delete Item](#delete-item)

## Get List

This endpoint returns the list of forms.

### Method

GET `/forms`

### Parameters

#### Query Parameters

| Name | Type | Description | Example |
| :--- | :--- | :---------- | :------ |
| id | integer | Filter forms by the given id. __Optional.__ | `id=1`<br><br>`id[]=1&id[]=2` |
| uuid | string | Filter forms by the given uuid. __Optional.__ | `uuid=6cb951d6-85e5-4670-a963-23ceb812dbd7`<br><br>`uuid[]=6cb951d6-85e5-4670-a963-23ceb812dbd7&uuid[]=ed31162c-6773-4caa-8661-c2c1575932c0` |
| createdAt[before] | string | Filter forms that were created before the given date. __Optional.__ | `createdAt[before]=2018-07-20T13:19:30.181Z` |
| createdAt[after] | string | Filter forms that were created after the given date. __Optional.__ | `createdAt[after]2018-07-20T13:19:30.181Z` |
| updatedAt[before] | string | Filter forms that were updated before the given date. __Optional.__ | `updatedAt[before]=2018-07-20T13:19:30.181Z` |
| updatedAt[after] | string | Filter forms that were updated after the given date. __Optional.__ | `updatedAt[after]=2018-07-20T13:19:30.181Z` |
| owner | string | Filter forms by the given owner. __Optional.__ | `owner=BusinessUnit`<br><br>`owner[]=BusinessUnit&owner[]=Staff` |
| ownerUuid | string | Filter forms by the given owner uuid. __Optional.__ | `ownerUuid=5f4108bb-fa74-4c93-9bb1-9e37d9302640`<br><br>`ownerUuid[]=5f4108bb-fa74-4c93-9bb1-9e37d9302640&ownerUuid[]=0092e830-e411-47cf-b7ef-c19cc79ba8cb` |
| type | string | Filter forms by exact type. __Optional.__ | `type=formio`<br><br>`type[]=formio&type[]=symfony` |
| page | integer | The current page in the pagination. __Optional.__ Default: `1`. | `page=2` |
| limit | integer | The number of items per page. __Optional.__ Default: `10`. | `limit=25` |
| order[id] | string | Order forms by id. __Optional.__ Options: `asc`, `desc`. | `order[id]=asc` |
| order[createdAt] | string | Order forms by creation date. __Optional.__ Options: `asc`, `desc`. | `order[createdAt]=asc` |
| order[updatedAt] | string | Order forms by modification date. __Optional.__ Options: `asc`, `desc`. | `order[updatedAt]=asc` |
| order[deletedAt] | string | Order forms by deletion date. __Optional.__ Options: `asc`, `desc`. | `order[deletedAt]=asc` |
| order[owner] | string | Order forms by owner. __Optional.__ | `order[owner]=asc` |

### Response

#### 200 OK

The request was successful and returns a JSON array of objects. Each object contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| id | integer | The form id. |
| uuid | string | The form uuid. |
| createdAt | string | The date the form was created on. |
| updatedAt | string | The date the form was update at. |
| deletedAt | string | The date the form was update at. |
| owner | string | The form owner. |
| ownerUuid | string | The form owner uuid. |
| type | string | The form type. Possible values: `formio`. |
| config | object | The form configurations. The config holds the properties for the given form type strategy. |
| version | integer | The form version. This value is used for optimistic locking. |
| tenant | string | The form tenant uuid. |

### Example

#### Request

*Method:*

__GET__ /forms

*Headers:*

```yaml
Accept: application/json
```

#### Response

*Code:*

`200 OK`

*Body:*

```json
[
  {
    "id": 1,
    "uuid": "6cb951d6-85e5-4670-a963-23ceb812dbd7",
    "createdAt": "2018-09-19T14:25:08+00:00",
    "updatedAt": "2018-09-19T14:25:08+00:00",
    "deletedAt": null,
    "owner": "BusinessUnit",
    "ownerUuid": "5f4108bb-fa74-4c93-9bb1-9e37d9302640",
    "type": "formio",
    "config": {
      "title": "Title 1",
      "display": "form",
      "type": "form",
      "name": "name-1",
      "path": "slug-1",
      "tags": [
        "tag-1"
      ],
      "components": [
        {
          "input": true,
          "tableView": true,
          "inputType": "text",
          "inputMask": "",
          "label": "Description",
          "key": "description",
          "placeholder": "",
          "prefix": "",
          "suffix": "",
          "multiple": false,
          "defaultValue": "",
          "protected": false,
          "unique": false,
          "persistent": true,
          "hidden": false,
          "clearOnHide": true,
          "validate": {
            "required": true,
            "minLength": "",
            "maxLength": "",
            "pattern": "",
            "custom": "",
            "customPrivate": false
          },
          "conditional": {
            "show": "",
            "when": null,
            "eq": ""
          },
          "type": "textfield",
          "hideLabel": false,
          "labelPosition": "top",
          "tags": [],
          "properties": []
        },
        {
          "input": true,
          "label": "Submit",
          "tableView": false,
          "key": "submit",
          "size": "md",
          "leftIcon": "",
          "rightIcon": "",
          "block": false,
          "action": "submit",
          "disableOnInvalid": false,
          "theme": "primary",
          "type": "button",
          "hideLabel": false
        }
      ],
      "submissionAccess": [
        {
          "type": "create_all",
          "roles": []
        },
        {
          "type": "read_all",
          "roles": []
        },
        {
          "type": "update_all",
          "roles": []
        },
        {
          "type": "delete_all",
          "roles": []
        },
        {
          "type": "create_own",
          "roles": [
            "5ba252ff1e044a003dcc8d38"
          ]
        },
        {
          "type": "read_own",
          "roles": []
        },
        {
          "type": "update_own",
          "roles": []
        },
        {
          "type": "delete_own",
          "roles": []
        }
      ]
    },
    "version": 1,
    "tenant": "e5a2120d-6bf7-4c58-a900-bac1e55e986b"
  }
]
```

## GET Item

This endpoint returns a specific form.

### Method

GET `/forms/{uuid}`

### Parameters

#### Path Parameters

| Name | Type | Description | Example |
| :--- | :--- | :---------- | :------ |
| uuid | string | The uuid of the form. __Required.__ | `6cb951d6-85e5-4670-a963-23ceb812dbd7` |

### Response

#### 200 OK

The request was successful and returns a JSON object that contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| id | integer | The form id. |
| uuid | string | The form uuid. |
| createdAt | string | The date the form was created on. |
| updatedAt | string | The date the form was update at. |
| deletedAt | string | The date the form was update at. |
| owner | string | The form owner. |
| ownerUuid | string | The form owner uuid. |
| type | string | The form type. Possible values: `formio`. |
| config | object | The form configurations. The config holds the properties for the given form type strategy. |
| version | integer | The form version. This value is used for optimistic locking. |
| tenant | string | The form tenant uuid. |

#### 404 Not Found

The request was unsuccessful and returns a JSON object that contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| type | string | The error type. |
| title | string | The error title message. |
| detail | string | The error detail description. |

### Example

#### Request

*Method:*

__GET__ `/forms/6cb951d6-85e5-4670-a963-23ceb812dbd7`

*Headers:*

```yaml
Accept: application/json
```

#### Response

*Code:*

`200 OK`

*Body:*

```json
{
  "id": 1,
  "uuid": "6cb951d6-85e5-4670-a963-23ceb812dbd7",
  "createdAt": "2018-09-19T14:25:08+00:00",
  "updatedAt": "2018-09-19T14:25:08+00:00",
  "deletedAt": null,
  "owner": "BusinessUnit",
  "ownerUuid": "5f4108bb-fa74-4c93-9bb1-9e37d9302640",
  "type": "formio",
  "config": {
    "title": "Title 1",
    "display": "form",
    "type": "form",
    "name": "name-1",
    "path": "slug-1",
    "tags": [
      "tag-1"
    ],
    "components": [
      {
        "input": true,
        "tableView": true,
        "inputType": "text",
        "inputMask": "",
        "label": "Description",
        "key": "description",
        "placeholder": "",
        "prefix": "",
        "suffix": "",
        "multiple": false,
        "defaultValue": "",
        "protected": false,
        "unique": false,
        "persistent": true,
        "hidden": false,
        "clearOnHide": true,
        "validate": {
          "required": true,
          "minLength": "",
          "maxLength": "",
          "pattern": "",
          "custom": "",
          "customPrivate": false
        },
        "conditional": {
          "show": "",
          "when": null,
          "eq": ""
        },
        "type": "textfield",
        "hideLabel": false,
        "labelPosition": "top",
        "tags": [],
        "properties": []
      },
      {
        "input": true,
        "label": "Submit",
        "tableView": false,
        "key": "submit",
        "size": "md",
        "leftIcon": "",
        "rightIcon": "",
        "block": false,
        "action": "submit",
        "disableOnInvalid": false,
        "theme": "primary",
        "type": "button",
        "hideLabel": false
      }
    ],
    "submissionAccess": [
      {
        "type": "create_all",
        "roles": []
      },
      {
        "type": "read_all",
        "roles": []
      },
      {
        "type": "update_all",
        "roles": []
      },
      {
        "type": "delete_all",
        "roles": []
      },
      {
        "type": "create_own",
        "roles": [
          "5ba252ff1e044a003dcc8d38"
        ]
      },
      {
        "type": "read_own",
        "roles": []
      },
      {
        "type": "update_own",
        "roles": []
      },
      {
        "type": "delete_own",
        "roles": []
      }
    ]
  },
  "version": 1,
  "tenant": "e5a2120d-6bf7-4c58-a900-bac1e55e986b"
}
```

## Add Item

This endpoint adds a form to the list.

### Method

POST `/forms`

### Parameters

#### Body

A JSON object that contains the following properties:

| Name | Type | Description | Example |
| :--- | :--- | :---------- | :------ |
| uuid | string | The form uuid. __Optional.__ Default: auto-generated. | `6cb951d6-85e5-4670-a963-23ceb812dbd7` |
| owner | string | The form owner. __Required.__ | `BusinessUnit` |
| ownerUuid | string | The form owner uuid. __Optional.__ Default: `null`. | `5f4108bb-fa74-4c93-9bb1-9e37d9302640` |
| type | string | The form type. __Required.__ Possible values: `formio`. | `formio` |
| config | object | The form configurations. The config holds the properties for the given form type strategy. __Required.__ | `{ "title": "Title 1", "display": "form", "type": "form", "name": "name-1", "path": "slug-1", "tags": ["tag-1"], "components": [}`, "submissionAccess": [] }` |
| version | integer | The form version. This value is used for optimistic locking. __Required.__ | `1` |

### Response

#### 200 OK

The request was successful and returns a JSON object that contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| id | integer | The form id. |
| uuid | string | The form uuid. |
| createdAt | string | The date the form was created on. |
| updatedAt | string | The date the form was update at. |
| deletedAt | string | The date the form was update at. |
| owner | string | The form owner. |
| ownerUuid | string | The form owner uuid. |
| type | string | The form type. Possible values: `formio`. |
| config | object | The form configurations. The config holds the properties for the given form type strategy. |
| version | integer | The form version. This value is used for optimistic locking. |
| tenant | string | The form tenant uuid. |

#### 400 Bad Request

The request was unsuccessful and and returns a JSON object that contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| type | string | The error type. |
| title | string | The error title message. |
| detail | string | The error detail description. |
| violations | array | The array of violations. |

### Example

#### Request

*Method:*

__POST__ `/forms`

*Headers:*

```yaml
Accept: application/json
```

*Body:*

```json
{
  "owner": "BusinessUnit",
  "ownerUuid": "5f4108bb-fa74-4c93-9bb1-9e37d9302640",
  "type": "formio",
  "config": {
    "title": "Title 1",
    "display": "form",
    "type": "form",
    "name": "name-1",
    "path": "slug-1",
    "tags": [
      "tag-1"
    ],
    "components": [
      {
        "input": true,
        "tableView": true,
        "inputType": "text",
        "inputMask": "",
        "label": "Description",
        "key": "description",
        "placeholder": "",
        "prefix": "",
        "suffix": "",
        "multiple": false,
        "defaultValue": "",
        "protected": false,
        "unique": false,
        "persistent": true,
        "hidden": false,
        "clearOnHide": true,
        "validate": {
          "required": true,
          "minLength": "",
          "maxLength": "",
          "pattern": "",
          "custom": "",
          "customPrivate": false
        },
        "conditional": {
          "show": "",
          "when": null,
          "eq": ""
        },
        "type": "textfield",
        "hideLabel": false,
        "labelPosition": "top",
        "tags": [],
        "properties": []
      },
      {
        "input": true,
        "label": "Submit",
        "tableView": false,
        "key": "submit",
        "size": "md",
        "leftIcon": "",
        "rightIcon": "",
        "block": false,
        "action": "submit",
        "disableOnInvalid": false,
        "theme": "primary",
        "type": "button",
        "hideLabel": false
      }
    ],
    "submissionAccess": [
      {
        "type": "create_all",
        "roles": []
      },
      {
        "type": "read_all",
        "roles": []
      },
      {
        "type": "update_all",
        "roles": []
      },
      {
        "type": "delete_all",
        "roles": []
      },
      {
        "type": "create_own",
        "roles": [
          "5ba252ff1e044a003dcc8d38"
        ]
      },
      {
        "type": "read_own",
        "roles": []
      },
      {
        "type": "update_own",
        "roles": []
      },
      {
        "type": "delete_own",
        "roles": []
      }
    ]
  },
  "version": 1
}
```

#### Response

*Code:*

`200 OK`

*Body:*

```json
{
  "id": 1,
  "uuid": "6cb951d6-85e5-4670-a963-23ceb812dbd7",
  "createdAt": "2018-09-19T14:25:08+00:00",
  "updatedAt": "2018-09-19T14:25:08+00:00",
  "deletedAt": null,
  "owner": "BusinessUnit",
  "ownerUuid": "5f4108bb-fa74-4c93-9bb1-9e37d9302640",
  "type": "formio",
  "config": {
    "title": "Title 1",
    "display": "form",
    "type": "form",
    "name": "name-1",
    "path": "slug-1",
    "tags": [
      "tag-1"
    ],
    "components": [
      {
        "input": true,
        "tableView": true,
        "inputType": "text",
        "inputMask": "",
        "label": "Description",
        "key": "description",
        "placeholder": "",
        "prefix": "",
        "suffix": "",
        "multiple": false,
        "defaultValue": "",
        "protected": false,
        "unique": false,
        "persistent": true,
        "hidden": false,
        "clearOnHide": true,
        "validate": {
          "required": true,
          "minLength": "",
          "maxLength": "",
          "pattern": "",
          "custom": "",
          "customPrivate": false
        },
        "conditional": {
          "show": "",
          "when": null,
          "eq": ""
        },
        "type": "textfield",
        "hideLabel": false,
        "labelPosition": "top",
        "tags": [],
        "properties": []
      },
      {
        "input": true,
        "label": "Submit",
        "tableView": false,
        "key": "submit",
        "size": "md",
        "leftIcon": "",
        "rightIcon": "",
        "block": false,
        "action": "submit",
        "disableOnInvalid": false,
        "theme": "primary",
        "type": "button",
        "hideLabel": false
      }
    ],
    "submissionAccess": [
      {
        "type": "create_all",
        "roles": []
      },
      {
        "type": "read_all",
        "roles": []
      },
      {
        "type": "update_all",
        "roles": []
      },
      {
        "type": "delete_all",
        "roles": []
      },
      {
        "type": "create_own",
        "roles": [
          "5ba252ff1e044a003dcc8d38"
        ]
      },
      {
        "type": "read_own",
        "roles": []
      },
      {
        "type": "update_own",
        "roles": []
      },
      {
        "type": "delete_own",
        "roles": []
      }
    ]
  },
  "version": 1,
  "tenant": "e5a2120d-6bf7-4c58-a900-bac1e55e986b"
}
```

## Edit Item

This endpoint edits a specific form.

### Method

PUT `/forms/{uuid}`

### Parameters

#### Path Parameters

| Name | Type | Description | Example |
| :--- | :--- | :---------- | :------ |
| uuid | string | The uuid of the scenario. __Required.__ | `6cb951d6-85e5-4670-a963-23ceb812dbd7` |

#### Body

A JSON object that contains the following properties:

| Name | Type | Description | Example |
| :--- | :--- | :---------- | :------ |
| uuid | string | The form uuid. __Optional.__ | `6cb951d6-85e5-4670-a963-23ceb812dbd7` |
| owner | string | The form owner. __Optional.__ | `BusinessUnit` |
| ownerUuid | string | The form owner uuid. __Optional.__ | `5f4108bb-fa74-4c93-9bb1-9e37d9302640` |
| type | string | The form type. Possible values: `formio`. __Optional__. | `formio` |
| config | object | The form configurations. The config holds the properties for the given form type strategy. __Required if a different type is provided.__ | `{ "title": "Title 1", "display": "form", "type": "form", "name": "name-1", "path": "slug-1", "tags": ["tag-1"], "components": [}`, "submissionAccess": [] }` |
| version | integer | The form version. This value is used for optimistic locking. __Required.__ | `1` |

### Response

#### 200 OK

The request was successful and returns a JSON object that contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| id | integer | The form id. |
| uuid | string | The form uuid. |
| createdAt | string | The date the form was created on. |
| updatedAt | string | The date the form was update at. |
| owner | string | The form owner. |
| ownerUuid | string | The form owner uuid. |
| type | string | The form type. |
| version | integer | The form version. This value is used for optimistic locking. |
| tenant | string | The form tenant uuid. |

#### 400 Bad Request

The request was unsuccessful and returns a JSON object that contains the following properties:

| Name | Type | Description |
| :--- | :--- | :---------- |
| type | string | The error type. |
| title | string | The error title message. |
| detail | string | The error compiled violations. |
| violations | array | The array of violations. |

### Example

#### Request

*Method:*

__PUT__ `/forms/6cb951d6-85e5-4670-a963-23ceb812dbd7`

*Headers:*

```yaml
Accept: application/json
```

*Body:*

```json
{
  "owner": "BusinessUnit",
  "ownerUuid": "5f4108bb-fa74-4c93-9bb1-9e37d9302640",
  "type": "formio",
  "config": {
    "title": "Test 1",
    "type": "form",
    "display": "form",
    "name": "test-1",
    "path": "test-1",
    "components": {},
    "submissionAccess": []
  },
  "version": 1
}
```

#### Response

*Code:*

`200 OK`

*Body:*

```json
{
  "id": 1,
  "uuid": "6cb951d6-85e5-4670-a963-23ceb812dbd7",
  "createdAt": "2018-09-19T14:25:08+00:00",
  "updatedAt": "2018-09-19T14:25:08+00:00",
  "deletedAt": null,
  "owner": "BusinessUnit",
  "ownerUuid": "5f4108bb-fa74-4c93-9bb1-9e37d9302640",
  "type": "formio",
  "config": {
    "title": "Test 1",
    "type": "form",
    "display": "form",
    "name": "test-1",
    "path": "test-1",
    "components": {},
    "submissionAccess": []
  },
  "version": 1,
  "tenant": "e5a2120d-6bf7-4c58-a900-bac1e55e986b"
}
```

## Delete Item

This endpoint deletes a specific form from the list.

### Method

DELETE `/forms/{uuid}`

### Parameters

#### Path Parameters

| Name | Type | Description | Example |
| :--- | :--- | :---------- | :------ |
| uuid | string | The uuid of the form. __Required.__ | `6cb951d6-85e5-4670-a963-23ceb812dbd7` |

### Response

#### 204 No Content

The request was successful and returns no content.

### Example

#### Request

*Method:*

__DELETE__ `/forms/6cb951d6-85e5-4670-a963-23ceb812dbd7`

#### Response

*Code:*

`204 No Content`

*Body:*

```

```
