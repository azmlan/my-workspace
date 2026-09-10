# Landing Page — Seed Content

Fill in the values below (delete the example text, keep the labels). Leave anything optional blank if you don't have it yet — it just won't render on the page until you add it later from `/backstage`.

Tell me when this is filled in and I'll build the seeder from it.

---

## Hero section (top of page)

- **الاسم الكامل** (full_name) — required: عبدالعزيز
- **العنوان الفرعي / التخصص** (tagline) — required, e.g. "مطور ويب Full-Stack": مهندس برمجيات - Fullstack Developer 
- **نبذة قصيرة** (bio_short) — required, 1-2 sentences shown under the tagline: أبني أنظمة برمجية باحترافية، وأهتم بجودة الكود واتباع أفضل الممارسات في كل مرحلة من التطوير.
- **GitHub URL** (optional):https://www.github.com/azmlan
- **LinkedIn URL** (optional): https://www.linkedin.com/in/abdulaziz-alanazi-aa905922b/
- **Twitter/X URL** (optional):
- **البريد الإلكتروني الظاهر للزوار** (email_display, optional): azmlan.dev@gmail.com

Media (optional, skip for now if you don't have files ready — you can upload these later from the dashboard instead):
- **صورة شخصية (Hero photo)**: skip / or give me a local file path
- **ملف السيرة الذاتية (CV/PDF)**: skip / or give me a local file path

---

## About section ("من انا")

- **نبذة كاملة عنك** (bio_full) — required, this is the full paragraph(s) — line breaks are preserved, so write it as normal paragraphs:
أبني الأنظمة، بدءًا من التصميم المعماري حتى إطلاق النظام. ما يهمني أكثر هو القرارات التي تُتخذ قبل كتابة الكود، لأنها الأساس اللي تُبنى عليه جودة النظام وموثوقيته على المدى الطويل

أعمال حديثة:

نظام إدارة أداء ومساهمات الموظفين
نظام متكامل مبني بأسلوب الـ Modular Monolith، يتكوّن من 10 وحدات وظيفية منفصلة، ومبني على ASP.NET Core 8. يخدم لوحتين منفصلتين — واحدة للإدارة وأخرى للموظفين — مبنيتين بـ React. من أبرز مزاياه: مسار مراجعة إيداعات يمر بعدة مراحل ويكتشف التكرار تلقائيًا، ونظام دعم فني داخلي مرتبط بالبريد الإلكتروني بشكل ثنائي الاتجاه، بالإضافة إلى مراقبة شاملة للنظام عبر Serilog وApp Insights على Azure.

نظام إدارة محتوى جامعي ثنائي اللغة (عربي/إنجليزي)
منصة لإدارة المحتوى تتيح نشر المحتوى بعد مرحلتي موافقة، مع صلاحيات مخصصة لكل قسم حسب دور المستخدم، ونظام أرشفة للإصدارات يسمح بالتراجع لأي نسخة سابقة بنقرة واحدة. النظام مُحوسب بالكامل (containerized)، ويعكس البنية الهرمية للجامعة: كلية ← قسم ← برنامج ← هيئة تدريس.

نظام إدارة حضور الاختبارات
حوّلت من خلاله عملية ورقية يدوية بالكامل إلى نظام إلكتروني يمر بمرحلتي موافقة، مع تقارير خاصة لكل كلية ولوحة تحكم إدارية شاملة.

تطبيق دليل بطاقات الائتمان والتوصيات (مشروع شخصي)
تطبيق جوال مع لوحة تحكم، يضم محرك توصيات يعتمد على نمط حياة المستخدم، ونظام تسجيل دخول بدون كلمة مرور عبر رمز تحقق (OTP)، وخاصية عرض ترويجي محدود بوقت. بنيته بالكامل بمفردي، ومُحوسب.


Media (optional): **صورة القسم**: skip / or local file path

---

## Services (الخدمات) — repeat this block for each service, at least 1

For each service give me:
- **العنوان** (title): تطوير المواقع الالكترونية
- **الوصف** (description): بناء المواقع الالكترونية حسب نشاط\احتياج العميل 
- **الأيقونة** (icon) — pick one of: `heroicon-o-code-bracket` (كود), `heroicon-o-device-phone-mobile` (موبايل), `heroicon-o-server` (سيرفر), `heroicon-o-paint-brush` (تصميم), `heroicon-o-cloud` (سحابة), `heroicon-o-cog-6-tooth` (إعدادات), `heroicon-o-chart-bar` (تحليلات), `heroicon-o-cube` (منتج) — or leave blank for a default icon.



For each service give me:
- **العنوان** (title): تطوير تطبيقات الجوال
- **الوصف** (description): بناء تطبيقات الجوال وربطها مع الموقع الالكتروني ان وجد  IOS - Android      
- **الأيقونة** (icon) — pick one of: `heroicon-o-code-bracket` (كود), `heroicon-o-device-phone-mobile` (موبايل), `heroicon-o-server` (سيرفر), `heroicon-o-paint-brush` (تصميم), `heroicon-o-cloud` (سحابة), `heroicon-o-cog-6-tooth` (إعدادات), `heroicon-o-chart-bar` (تحليلات), `heroicon-o-cube` (منتج) — or leave blank for a default icon.


For each service give me:
- **العنوان** (title): انشاء متجر في زد و سلة
- **الوصف** (description):انشاء متجر في زد و سلة وربط بوابة المدفوعات وخدمات التوصيل وجميع الخدمات الاحرى المتوفرة في المنصة       
- **الأيقونة** (icon) — pick one of: `heroicon-o-code-bracket` (كود), `heroicon-o-device-phone-mobile` (موبايل), `heroicon-o-server` (سيرفر), `heroicon-o-paint-brush` (تصميم), `heroicon-o-cloud` (سحابة), `heroicon-o-cog-6-tooth` (إعدادات), `heroicon-o-chart-bar` (تحليلات), `heroicon-o-cube` (منتج) — or leave blank for a default icon.


Example (delete before filling):
1. العنوان: تطوير مواقع | الوصف: بناء مواقع سريعة واحترافية | الأيقونة: heroicon-o-code-bracket

---
<!-- 
## Portfolio projects (مشاريعي) — repeat for each project, can be 0 if you want this section hidden for now

For each project:
- **العنوان** (title):
- **الوصف** (description):
- **الوسوم التقنية** (tech_tags) — comma separated, e.g. "Laravel, Vue, MySQL":
- **رابط المشروع المباشر** (live_url, optional):
- **رابط GitHub** (github_url, optional):
- **مميز؟** (featured — yes/no):
- **صورة المشروع**: skip / or local file path -->

---
<!-- 
## Testimonials (آراء العملاء) — repeat for each, can be 0 to hide this section for now

For each testimonial:
- **اسم العميل** (client_name) — required:
- **المسمى الوظيفي** (client_role, optional):
- **الشركة** (client_company, optional):
- **البريد الإلكتروني** (email, optional):
- **نص الرأي** (body) — required:
- **التقييم** (rating, 1-5, optional):
- **صورة العميل**: skip / or local file path

--- -->

## Notes on what I'll do with this

- Hero and About are single-row settings — I'll seed them once with whatever you give me.
- Services, Projects, and Testimonials are lists — I'll seed as many rows as you give me, `is_visible = true`, `sort_order` in the order you list them.
- Anything left blank/optional stays empty — the page already handles missing optional fields gracefully (it just hides that bit of UI).
- All of this becomes editable later from `/backstage` — the seed is just a starting point, not a lock-in.
