<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Content;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 Realistic Real Estate FAQ items for the dynamic contents table
        $faqs = [
            [
                'title'       => 'How do I post my property requirement?',
                'description' => '<p>You can post your property requirements completely free by navigating to our "Post your Requirement" page. Simply fill in the property type, size, preferred location, and your contact details. Our automated system will match your request with active developers and notify you.</p>',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Are there any fees or commissions for property buyers?',
                'description' => '<p>No, our portal is 100% free for property buyers and seekers. You can browse projects, compare listings, use the mortgage/EMI calculator, and contact developers directly without paying any commission or service fees.</p>',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'How long does it take for a developer account to be approved?',
                'description' => '<p>Once a developer registers their company profile, our admin team reviews the business credentials and trade license. This verification process typically takes between 12 to 24 hours. Once approved, the developer can start listing their projects immediately.</p>',
                'sort_order'  => 3,
            ],
            [
                'title'       => 'Can I compare multiple properties side by side?',
                'description' => '<p>Yes! You can compare up to 3 properties at a time. Simply click the balance scale icon on any property card to add it to the comparison bar. Once selected, click the "Compare Now" button to view a detailed, side-by-side comparison of prices, sizes, and specs.</p>',
                'sort_order'  => 4,
            ],
            [
                'title'       => 'What is a 360° Virtual Tour and how do I use it?',
                'description' => '<p>A 360° Virtual Tour allows you to explore an apartment in immersive 3D from the comfort of your home. If a property has a virtual tour uploaded by the developer, a "360° Tour" tab will appear on the details page. Click the tab and use your mouse (or finger on mobile) to drag and look around the property.</p>',
                'sort_order'  => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Content::create([
                'module'      => 'faq',
                'title'       => $faq['title'],
                'description' => $faq['description'],
                'sort_order'  => $faq['sort_order'],
                'status'      => 1, // 1 = Active
            ]);
        }
    }
}