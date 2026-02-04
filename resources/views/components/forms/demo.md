# X-Forms Component Library

A reusable, Bootstrap-compatible Blade component system for building consistent, validated forms in Laravel.

All components follow the same principles:

- **Blade-first**
- **Validation-friendly**
- **Works with old() & $errors**
- **Vanilla JavaScript only**
- **Bootstrap 5 compatible**
- **Accessible & extensible**

## Table of Contents

1. [Input Component](#1️⃣-input-component)
2. [Select Component](#2️⃣-select-component)
3. [Checkbox Component](#3️⃣-checkbox-component)
4. [Switch Component](#4️⃣-switch-component)
5. [File Upload Component](#5️⃣-file-upload-component)
6. [Form Component](#6️⃣-form-component)
7. [Common Validation Pattern](#7️⃣-validation-pattern-recommended)

---

## 1️⃣ Input Component

**Component**
```blade
<x-forms.input />
```

### Supported Types

- `text`
- `email`
- `password` (with show/hide toggle)
- `number`
- `date`
- `time`
- `hidden`

### Example

```blade
<x-forms.input
    type="email"
    id="email"
    name="email"
    label="Email Address"
    required
    :error="$errors->first('email')"
/>
```

### Password Input (Auto Toggle)

```blade
<x-forms.input
    type="password"
    id="password"
    name="password"
    label="Password"
    required
    :error="$errors->first('password')"
/>
```

### Props

| Prop | Type | Description |
|------|------|-------------|
| `type` | string | Input type |
| `id` | string | Input id |
| `name` | string | Input name |
| `label` | string | Label text |
| `required` | boolean | Required field |
| `variant` | string | `default` / `floating` |
| `error` | string | Validation message |

---

## 2️⃣ Select Component

**Component**
```blade
<x-forms.select />
```

### Example

```blade
<x-forms.select
    id="role"
    name="role"
    label="User Role"
    :options="[
        'admin' => 'Admin',
        'user' => 'User'
    ]"
    required
    :error="$errors->first('role')"
/>
```

### Props

| Prop | Type | Description |
|------|------|-------------|
| `options` | array | Key-value options |
| `selected` | mixed | Selected value |
| `placeholder` | string | Default option |

---

## 3️⃣ Checkbox Component

**Component**
```blade
<x-forms.checkbox />
```

### Example

```blade
<x-forms.checkbox
    id="terms"
    name="terms"
    label="I accept the terms"
    required
    :error="$errors->first('terms')"
/>
```

### Inline Checkbox

```blade
<x-forms.checkbox
    id="remember"
    name="remember"
    label="Remember me"
/>
```

---

## 4️⃣ Switch Component

Toggle-style checkbox using Bootstrap + custom CSS.

**Component**
```blade
<x-forms.switch />
```

### Example

```blade
<x-forms.switch
    id="status"
    name="status"
    label="Active Status"
    checked
/>
```

### Props

| Prop | Type | Description |
|------|------|-------------|
| `checked` | boolean | Default ON |
| `disabled` | boolean | Disable switch |

---

## 5️⃣ File Upload Component

**Component**
```blade
<x-forms.file />
```

### Features

- Drag & drop
- Click to browse
- Image / video / document preview
- Single & multiple uploads
- Full / square / circle shapes
- Validation support
- Remove individual files

### Basic Example

```blade
<x-forms.file
    id="document"
    name="document"
    label="Upload File"
    :error="$errors->first('document')"
/>
```

### Multiple Files (Preview Enabled)

```blade
<x-forms.file
    id="attachments"
    name="attachments"
    label="Attachments"
    multiple
    preview
/>
```

### Profile Image (Circle)

```blade
<x-forms.file
    id="avatar"
    name="avatar"
    label="Profile Picture"
    shape="circle"
    accept="image/*"
    preview
/>
```

### Props

| Prop | Type | Description |
|------|------|-------------|
| `name` | string | Input name |
| `multiple` | boolean | Allow multiple files |
| `preview` | boolean | Enable preview |
| `shape` | string | `full` / `square` / `circle` |
| `accept` | string | File types |
| `error` | string | Validation error |

---

## 6️⃣ Form Component

**Component**
```blade
<x-forms.form />
```

### Supported Methods

- `GET`
- `POST`
- `PUT`
- `PATCH`
- `DELETE`

### Features

- Auto CSRF
- Auto method spoofing
- Reactive submit state
- Spinner + disabled button
- Optional confirmation modal

### Basic Form

```blade
<x-forms.form action="{{ route('users.store') }}" method="POST">
    <x-forms.input name="name" label="Name" />
    <button type="submit" class="btn btn-primary">Save</button>
</x-forms.form>
```

### Reactive Form (Processing State)

```blade
<x-forms.form
    action="{{ route('users.store') }}"
    method="POST"
    varient="reactive"
    processing-text="Saving..."
>
    <button type="submit" class="btn btn-success">
        Submit
    </button>
</x-forms.form>
```

### Form With Confirmation

```blade
<x-forms.form
    action="{{ route('users.destroy', $user) }}"
    method="DELETE"
    confirm
    confirm-title="Delete User"
    confirm-message="This action cannot be undone"
>
    <button type="submit" class="btn btn-danger">
        Delete
    </button>
</x-forms.form>
```

---

## 7️⃣ Validation Pattern (Recommended)

Always pass validation errors explicitly:

```blade
<x-forms.input
    name="email"
    label="Email"
    :error="$errors->first('email')"
/>
```

This ensures:

- Blade-only validation
- No JS dependency
- Works with redirects & old input

---

## ✅ Design Principles

- ❌ No Alpine / jQuery
- ✅ Vanilla JS only
- ✅ Bootstrap 5 compatible
- ✅ Laravel validation friendly
- ✅ Extendable & maintainable

---

## 🔜 Planned Enhancements

- Async uploads
- Progress bars
- Existing file previews (edit forms)
- Client-side validation
- Drag reorder for multiple files