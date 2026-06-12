<?php

namespace Database\Seeders;

use App\Models\GlobalTemplate;
use Illuminate\Database\Seeder;

class GlobalTemplateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // $data_json = '{
    //         "id": "etoile-premium",
    //         "categoryId": "fnb",
    //         "subCategory": "fine_dining",
    //         "brand": {
    //           "name": "ÉTOILE",
    //           "primaryColor": "#C35B3E",
    //           "accentColor": "#1A1A1A",
    //           "fontPairing": {
    //             "heading": "Playfair Display",
    //             "body": "Inter"
    //           }
    //         },
    //         "seo": {
    //           "title": "Étoile — A Modern Culinary Symphony",
    //           "description": "Fine dining in Bandung. Avant-garde techniques, local terroirs, and architectural plating.",
    //           "keywords": [
    //             "fine dining",
    //             "restaurant",
    //             "bandung",
    //             "fnb",
    //             "etoile"
    //           ],
    //           "ogImage": "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2070&auto=format&fit=crop"
    //         },
    //         "social": {
    //           "instagram": "#",
    //           "twitter": "#"
    //         },
    //         "sections": {
    //           "promotions": {
    //             "enabled": true,
    //             "order": 0,
    //             "title": "Announcements",
    //             "items": [
    //               {
    //                 "id": "p1",
    //                 "title": "NOW ACCEPTING RESERVATIONS FOR NEW YEAR EVE DINNER",
    //                 "description": ""
    //               },
    //               {
    //                 "id": "p2",
    //                 "title": "•",
    //                 "description": ""
    //               },
    //               {
    //                 "id": "p3",
    //                 "title": "COMPLIMENTARY WELCOME DRINK FOR WEEKDAY LUNCH",
    //                 "description": ""
    //               },
    //               {
    //                 "id": "p4",
    //                 "title": "•",
    //                 "description": ""
    //               },
    //               {
    //                 "id": "p5",
    //                 "title": "MICHELIN STAR GUEST CHEF SERIES THIS NOVEMBER",
    //                 "description": ""
    //               }
    //             ]
    //           },
    //           "hero": {
    //             "enabled": true,
    //             "order": 1,
    //             "headline": "Sensory",
    //             "subheadline": "A Modern Culinary Symphony.",
    //             "cta": {
    //               "primary": {
    //                 "label": "Reserve a Table",
    //                 "href": "#reservations"
    //               }
    //             },
    //             "backgroundImage": {
    //               "src": "https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?q=80&w=2070&auto=format&fit=crop",
    //               "alt": "Restaurant ambiance"
    //             },
    //             "badge": "EST. 2024 / BANDUNG"
    //           },
    //           "about": {
    //             "enabled": true,
    //             "order": 2,
    //             "title": "Our Genesis",
    //             "story": "Born from a restless desire to deconstruct the boundaries of fine dining, Étoile strips away the archaic pretense of gastronomy. We source relentlessly, cook precisely, and plate with an architectural mindset. Every dish is an homage to local terroirs, reimagined through avant-garde techniques.",
    //             "highlights": [
    //               {
    //                 "icon": "star",
    //                 "value": "3",
    //                 "label": "Hat Awards",
    //                 "suffix": "+"
    //               },
    //               {
    //                 "icon": "wine",
    //                 "value": "850",
    //                 "label": "Wine Labels",
    //                 "suffix": "+"
    //               },
    //               {
    //                 "icon": "chef",
    //                 "value": "100",
    //                 "label": "Farm Partners",
    //                 "suffix": "%"
    //               }
    //             ],
    //             "image": {
    //               "src": "https://images.unsplash.com/photo-1600891964092-4316c288032e?q=80&w=2070&auto=format&fit=crop",
    //               "alt": "Culinary art"
    //             },
    //             "foundedYear": 2024
    //           },
    //           "menu": {
    //             "enabled": true,
    //             "order": 3,
    //             "title": "Curated Selections",
    //             "categories": [
    //               {
    //                 "id": "signatures",
    //                 "name": "Signatures"
    //               }
    //             ],
    //             "items": [
    //               {
    //                 "id": "m1",
    //                 "name": "Charred Octopus",
    //                 "description": "Squid ink emulsion, confit potato, smoked paprika",
    //                 "price": 34,
    //                 "currency": "USD",
    //                 "categoryId": "signatures",
    //                 "image": {
    //                   "src": "https://images.unsplash.com/photo-1574966739987-65e386c9f692?q=80&w=1000&auto=format&fit=crop",
    //                   "alt": "Charred Octopus"
    //                 },
    //                 "tags": [],
    //                 "featured": true
    //               },
    //               {
    //                 "id": "m2",
    //                 "name": "Wagyu A5 Striploin",
    //                 "description": "Maitake mushrooms, black garlic jus, bone marrow",
    //                 "price": 85,
    //                 "currency": "USD",
    //                 "categoryId": "signatures",
    //                 "image": {
    //                   "src": "https://images.unsplash.com/photo-1544025162-836b7012984b?q=80&w=1000&auto=format&fit=crop",
    //                   "alt": "Wagyu A5 Striploin"
    //                 },
    //                 "tags": [],
    //                 "featured": true
    //               },
    //               {
    //                 "id": "m3",
    //                 "name": "Truffle Capellini",
    //                 "description": "Hand-made pasta, pecorino romano, shaved winter truffle",
    //                 "price": 42,
    //                 "currency": "USD",
    //                 "categoryId": "signatures",
    //                 "image": {
    //                   "src": "https://images.unsplash.com/photo-1473093295043-cdd812d0e601?q=80&w=1000&auto=format&fit=crop",
    //                   "alt": "Truffle Capellini"
    //                 },
    //                 "tags": [
    //                   "vegetarian"
    //                 ]
    //               }
    //             ],
    //             "ctaLabel": "View Full Menu",
    //             "ctaHref": "#menu"
    //           },
    //           "gallery": {
    //             "enabled": true,
    //             "order": 4,
    //             "title": "The Ambience",
    //             "layout": "masonry",
    //             "images": [
    //               {
    //                 "src": "https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?q=80&w=1000&auto=format&fit=crop",
    //                 "alt": "Gallery 1"
    //               },
    //               {
    //                 "src": "https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1000&auto=format&fit=crop",
    //                 "alt": "Gallery 2"
    //               },
    //               {
    //                 "src": "https://images.unsplash.com/photo-1544148103-0773bf10d330?q=80&w=1000&auto=format&fit=crop",
    //                 "alt": "Gallery 3"
    //               },
    //               {
    //                 "src": "https://images.unsplash.com/photo-1578474846511-04ba529f0b88?q=80&w=1000&auto=format&fit=crop",
    //                 "alt": "Gallery 4"
    //               },
    //               {
    //                 "src": "https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1000&auto=format&fit=crop",
    //                 "alt": "Gallery 5"
    //               }
    //             ]
    //           },
    //           "testimonials": {
    //             "enabled": true,
    //             "order": 5,
    //             "title": "Echoes",
    //             "averageRating": 4.9,
    //             "totalReviews": 342,
    //             "items": [
    //               {
    //                 "id": "t1",
    //                 "author": "The Michelin Guide",
    //                 "role": "Critic",
    //                 "content": "A masterful display of technique meeting raw emotion on a plate.",
    //                 "rating": 5
    //               },
    //               {
    //                 "id": "t2",
    //                 "author": "Vogue Culinary",
    //                 "role": "Magazine",
    //                 "content": "Étoile isn\'t just a restaurant; it\'s a sensory theater.",
    //                 "rating": 5
    //               },
    //               {
    //                 "id": "t3",
    //                 "author": "James R.",
    //                 "role": "Guest",
    //                 "content": "The Wagyu A5 was a transcendent experience. Flawless execution.",
    //                 "rating": 5
    //               }
    //             ]
    //           },
    //           "location": {
    //             "enabled": true,
    //             "order": 6,
    //             "address": {
    //               "street": "123 Culinary Blvd, Setiabudi",
    //               "city": "Bandung",
    //               "postalCode": "40115",
    //               "country": "Indonesia"
    //             },
    //             "hours": [
    //               {
    //                 "day": "monday",
    //                 "open": "",
    //                 "close": "",
    //                 "closed": true
    //               },
    //               {
    //                 "day": "tuesday",
    //                 "open": "",
    //                 "close": "",
    //                 "closed": true
    //               },
    //               {
    //                 "day": "wednesday",
    //                 "open": "17:00",
    //                 "close": "23:00"
    //               },
    //               {
    //                 "day": "thursday",
    //                 "open": "17:00",
    //                 "close": "23:00"
    //               },
    //               {
    //                 "day": "friday",
    //                 "open": "17:00",
    //                 "close": "23:00"
    //               },
    //               {
    //                 "day": "saturday",
    //                 "open": "17:00",
    //                 "close": "23:00"
    //               },
    //               {
    //                 "day": "sunday",
    //                 "open": "17:00",
    //                 "close": "23:00"
    //               }
    //             ],
    //             "phone": "+62 812 3456 7890",
    //             "email": "reservations@etoile.com",
    //             "mapEmbedUrl": "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.57311709235512!3d-6.903444341687889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid"
    //           }
    //         }
    //     }';

    // $data = json_decode($data_json, true);


    $filePath = database_path('seeders/template-data.json');
    $jsonString = file_get_contents($filePath);
    $dataArray = collect(json_decode($jsonString, true));

    foreach ($dataArray['data'] as $index => $data) {
      $brandSettings = [
        'brand' => $data['brand'] ?? [],
        'social' => $data['social'] ?? [],
      ];

      $contentBlocks = [];
      if (isset($data['sections'])) {
        foreach ($data['sections'] as $type => $sectionData) {
          $contentBlocks[] = [
            'type' => $type,
            'data' => $sectionData
          ];
        }
      }

      switch ($index) {
        case 0:
          $tenantCategory = \App\Models\Tenant\TenantCategory::where('code', 'FNB')->first();

          $template = GlobalTemplate::create(
            [
              'title' => 'Food and Beverage',
              'slug' => 'food-and-beverage',
              'tenant_category_id' => $tenantCategory?->id,
              'description' => 'A Modern Culinary Symphony template suitable for fine dining.',
              'brand_settings' => $brandSettings,
              'is_active' => 1,
            ]
          );

          \App\Models\GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'Home',
            'slug' => 'home',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;

        case 1:
          $tenantCategory = \App\Models\Tenant\TenantCategory::where('code', 'CMP')->first();

          $template = GlobalTemplate::create(
            [
              'title' => 'Company Profile',
              'slug' => 'company-profile',
              'tenant_category_id' => $tenantCategory?->id,
              'description' => 'A Sophisticated Blueprint of Innovation template suitable for corporate identities',
              'brand_settings' => $brandSettings,
              'is_active' => 1,
            ]
          );

          \App\Models\GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'Home',
            'slug' => 'home',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;

        case 2:
          $tenantCategory = \App\Models\Tenant\TenantCategory::where('code', 'RET')->first();

          $template = GlobalTemplate::create(
            [
              'title' => 'Retail',
              'slug' => 'retail',
              'tenant_category_id' => $tenantCategory?->id,
              'description' => 'A Vibrant Commerce Canvas template suitable for modern storefronts',
              'brand_settings' => $brandSettings,
              'is_active' => 1,
            ]
          );

          \App\Models\GlobalTemplatePage::create([
            'global_template_id' => $template->id,
            'title' => 'Home',
            'slug' => 'home',
            'content_blocks' => $contentBlocks,
            'meta' => ['seo' => $data['seo'] ?? []],
            'is_active' => 1,
          ]);
          break;
      }
      
    }
  }
}
