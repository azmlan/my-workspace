<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Settings\AboutSettings;
use App\Settings\HeroSettings;
use Illuminate\Database\Seeder;

class LandingSeeder extends Seeder
{
    public function run(): void
    {
        $hero = app(HeroSettings::class);
        $hero->full_name = 'عبدالعزيز';
        $hero->tagline = 'مهندس برمجيات - Fullstack Developer';
        $hero->bio_short = 'أبني أنظمة برمجية باحترافية، وأهتم بجودة الكود واتباع أفضل الممارسات في كل مرحلة من التطوير.';
        $hero->github_url = 'https://www.github.com/azmlan';
        $hero->linkedin_url = 'https://www.linkedin.com/in/abdulaziz-alanazi-aa905922b/';
        $hero->twitter_url = null;
        $hero->email_display = 'azmlan.dev@gmail.com';
        $hero->save();

        $about = app(AboutSettings::class);
        $about->bio_full = <<<'BIO'
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
BIO;
        $about->save();

        $services = [
            [
                'sort_order' => 1,
                'title' => 'تطوير المواقع الالكترونية',
                'description' => 'بناء المواقع الالكترونية حسب نشاط\احتياج العميل',
                'icon' => 'heroicon-o-code-bracket',
            ],
            [
                'sort_order' => 2,
                'title' => 'تطوير تطبيقات الجوال',
                'description' => 'بناء تطبيقات الجوال وربطها مع الموقع الالكتروني ان وجد IOS - Android',
                'icon' => 'heroicon-o-device-phone-mobile',
            ],
            [
                'sort_order' => 3,
                'title' => 'انشاء متجر في زد و سلة',
                'description' => 'انشاء متجر في زد و سلة وربط بوابة المدفوعات وخدمات التوصيل وجميع الخدمات الاخرى المتوفرة في المنصة',
                'icon' => 'heroicon-o-cube',
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['sort_order' => $service['sort_order']],
                [
                    'title' => $service['title'],
                    'description' => $service['description'],
                    'icon' => $service['icon'],
                    'is_visible' => true,
                ]
            );
        }
    }
}
