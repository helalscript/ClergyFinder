# Question Page – Implementation Plan

## Overview
Question Page যেখানে **Clergy** এবং **Seeker** দুই ধরনের ইউজারের জন্য আলাদা প্রশ্ন থাকবে। কিছু প্রশ্নে ইমেজ থাকবে, প্রতিটি প্রশ্নে **answer_type** থাকবে।

---

## 1. Database Design

### New Table: `questions`

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| user_type | enum | `clergy` / `seeker` |
| question | string | প্রশ্নের টেক্সট |
| image | string (nullable) | প্রশ্নের সাথে ইমেজ (optional) |
| answer_type | enum | উত্তর ধরন (নিচে বিস্তারিত) |
| answer | longText (nullable) | সঠিক উত্তর (admin এর জন্য) |
| sort_order | integer | ক্রম (ordering) |
| status | enum | `active` / `inactive` |
| timestamps | | created_at, updated_at |

### Answer Type Options (প্রস্তাবিত)

| Value | Description |
|-------|-------------|
| text | সাধারণ টেক্সট উত্তর |
| textarea | বড় টেক্সট/প্যারাগ্রাফ |
| single_choice | একটিমাত্র অপশন সিলেক্ট |
| multiple_choice | একাধিক অপশন সিলেক্ট |
| yes_no | হ্যাঁ/না |

*(প্রয়োজন অনুযায়ী আরও type যোগ করা যাবে)*

---

## 2. Enums

### `app/Enums/UserType.php` (নতুন)
```php
enum UserType: string
{
    case Clergy = 'clergy';
    case Seeker = 'seeker';
}
```

### `app/Enums/AnswerType.php` (নতুন)
```php
enum AnswerType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case SingleChoice = 'single_choice';
    case MultipleChoice = 'multiple_choice';
    case YesNo = 'yes_no';
}
```

### Page & Section (যদি CMS এ রাখতে চান)
- `Page::QuestionPage = 'question_page'`
- `Section::QuestionClergy`, `Section::QuestionSeeker` ইত্যাদি

*(এখানে আলাদা `questions` টেবিল ব্যবহার করাই ভালো – CMS থেকে আলাদা)*

---

## 3. Model

### `app/Models/Question.php`
- Fillable: `user_type`, `question`, `image`, `answer_type`, `answer`, `sort_order`, `status`
- Scopes: `clergy()`, `seeker()`, `active()`
- Image upload/delete helper ব্যবহার

---

## 4. Migration

### `create_questions_table.php`
- উপরের স্ট্রাকচার অনুযায়ী টেবিল তৈরি
- `user_type` এবং `answer_type` এর জন্য enum/string কলাম

---

## 5. Backend (Web CMS)

### Controller: `QuestionPageController`

**Option A – এক Controller, দুই সেকশন**
- `index($type)` – type = clergy / seeker
- `create($type)`
- `store($type)`
- `edit($id)`
- `update($id)`
- `destroy($id)`
- `status($id)`
- `updateOrder()` (যদি drag-drop ordering লাগে)

**Option B – দুই আলাদা Controller**
- `ClergyQuestionController`
- `SeekerQuestionController`

*(প্ল্যানে Option A ধরা হয়েছে – কম কোড, একই লজিক)*

### Routes (`routes/cms.php`)

```
cms/question-page/clergy          → index (clergy)
cms/question-page/clergy/create   → create
cms/question-page/clergy          → store (POST)
cms/question-page/clergy/{id}/edit → edit
cms/question-page/clergy/{id}     → update (PUT)
cms/question-page/clergy/{id}     → destroy (DELETE)
cms/question-page/clergy/status/{id} → status toggle

cms/question-page/seeker         → same pattern
```

---

## 6. Views (Backend)

### Folder Structure
```
resources/views/backend/layouts/cms/question_page/
├── clergy/
│   ├── index.blade.php    (DataTables, list)
│   ├── create.blade.php   (form: question, image dropify, answer_type select, answer)
│   └── edit.blade.php
└── seeker/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

**অথবা** একই ভিউ ফাইল, শুধু `$userType` (clergy/seeker) পাস করে – কম ডুপ্লিকেট।

### Form Fields (Create/Edit)
- Question (text/textarea)
- Image (Dropify, optional)
- Answer Type (select dropdown)
- Answer (text/textarea – admin reference)
- Status (toggle)
- Sort Order (number)

---

## 7. API (Frontend/Mobile)

### Controller: `app/Http/Controllers/API/V1/QuestionController.php`

**Endpoints:**
- `GET /api/questions/clergy` – clergy এর সব active প্রশ্ন
- `GET /api/questions/seeker` – seeker এর সব active প্রশ্ন

**Response structure:**
```json
{
  "status": true,
  "message": "Questions fetched successfully",
  "data": [
    {
      "id": 1,
      "question": "প্রশ্নের টেক্সট",
      "image": "url or null",
      "answer_type": "text",
      "sort_order": 1
    }
  ]
}
```

*(উত্তর admin এর জন্য, API তে পাঠানো হবে না – শুধু প্রশ্ন ও answer_type)*

---

## 8. Sidebar Menu

```
CMS
├── ...
├── Question Page
│   ├── Clergy Questions
│   └── Seeker Questions
```

---

## 9. Implementation Order

| Step | Task |
|------|------|
| 1 | Migration: `create_questions_table` |
| 2 | Enums: `UserType`, `AnswerType` |
| 3 | Model: `Question` |
| 4 | Routes: `cms.php` এ question-page routes |
| 5 | Controller: `QuestionPageController` |
| 6 | Views: index, create, edit (clergy + seeker) |
| 7 | Sidebar: Question Page menu |
| 8 | API Controller + API routes |
| 9 | (Optional) Answer options table – যদি single/multiple choice এ options আলাদা রাখতে হয় |

---

## 10. Optional: Answer Options (Multiple/Single Choice)

যদি `single_choice` বা `multiple_choice` এ প্রতিটি অপশন আলাদা রাখতে হয়:

### Table: `question_options`
| Column | Type |
|--------|------|
| id | bigint |
| question_id | foreignId |
| option_text | string |
| is_correct | boolean |
| sort_order | integer |

এটা পরে যোগ করা যাবে। প্রথমে শুধু `answer` টেক্সট ফিল্ডে JSON বা comma-separated রাখলেও চলে।

---

## Summary

- **2 user types:** Clergy, Seeker  
- **Image:** Optional per question (Dropify)  
- **answer_type:** text, textarea, single_choice, multiple_choice, yes_no  
- **Backend:** এক QuestionPageController, type অনুযায়ী clergy/seeker আলাদা  
- **API:** `/api/questions/clergy`, `/api/questions/seeker`  
- **Views:** Onboarding/State Licensing প্যাটার্ন অনুসরণ  

এই প্ল্যান ঠিক থাকলে পরবর্তী ধাপে implementation শুরু করা যাবে।
