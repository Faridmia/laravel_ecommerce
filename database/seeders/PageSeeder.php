<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Team;
use App\Models\Testimonial;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed About Us page metadata and structured text sections
        Page::updateOrCreate(['slug' => 'about'], [
            'title' => 'About Us',
            'description' => null, // Stored in static about.blade.php layout now
            'about_vision_title' => 'Our Vision',
            'about_vision_description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Phasellus hendrerit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh.',
            'about_mission_title' => 'Our Mission',
            'about_mission_description' => 'Sed egestas, ante et vulputate volutpat, eros pede semper est, vitae luctus metus libero eu augue. Morbi purus libero, faucibus adipiscing, commodo quis, gravida id, est. Sed lectus. Praesent elementum hendrerit tortor. Sed semper lorem at felis.',
            'about_who_we_are_title' => 'Who We Are',
            'about_who_we_are_description' => 'Pellentesque odio nisi, euismod pharetra a ultricies in diam. Sed arcu. Cras consequat. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Suspendisse potenti. Sed egestas, ante et vulputate volutpat, uctus metus libero eu augue.',
            'meta_title' => 'About Us - Molla eCommerce',
            'meta_description' => 'Learn more about Molla eCommerce, our vision, mission, and team.',
            'meta_keywords' => 'about, who we are, vision, mission, team',
        ]);

        // 2. Seed default Team Members
        Team::truncate();
        
        Team::create([
            'name' => 'Samanta Grey',
            'designation' => 'Founder & CEO',
            'image' => 'member-1.jpg',
            'facebook_link' => '#',
            'twitter_link' => '#',
            'instagram_link' => '#',
        ]);

        Team::create([
            'name' => 'Bruce Sutton',
            'designation' => 'Sales & Marketing Manager',
            'image' => 'member-2.jpg',
            'facebook_link' => '#',
            'twitter_link' => '#',
            'instagram_link' => '#',
        ]);

        Team::create([
            'name' => 'Janet Joy',
            'designation' => 'Product Manager',
            'image' => 'member-3.jpg',
            'facebook_link' => '#',
            'twitter_link' => '#',
            'instagram_link' => '#',
        ]);

        // 3. Seed default Customer Testimonials
        Testimonial::truncate();

        Testimonial::create([
            'name' => 'Jenson Gregory',
            'designation' => 'Customer',
            'review' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Pellentesque aliquet nibh nec urna. In nisi neque, aliquet vel, dapibus id, mattis vel, nisi. Sed pretium, ligula sollicitudin laoreet viverra, tortor libero sodales leo, eget blandit nunc tortor eu nibh. Nullam mollis. Ut justo. Suspendisse potenti.',
            'image' => 'user-1.jpg',
        ]);

        Testimonial::create([
            'name' => 'Victoria Ventura',
            'designation' => 'Customer',
            'review' => 'Impedit, ratione sequi, sunt incidunt magnam et. Delectus obcaecati optio eius error libero perferendis nesciunt atque dolores magni recusandae! Doloremque quidem error eum quis similique doloribus natus qui ut ipsum.Velit quos ipsa exercitationem, vel unde obcaecati impedit eveniet non.',
            'image' => 'user-2.jpg',
        ]);

        // 4. Seed Terms & Conditions
        $termsDescription = '
        <div class="row mt-4">
            <div class="col-12">
                <h3 class="mb-2">1. Introduction</h3>
                <p>Welcome to Molla eCommerce. These terms and conditions outline the rules and regulations for the use of our website.</p>
                
                <h3 class="mt-4 mb-2">2. Intellectual Property Rights</h3>
                <p>Other than the content you own, under these Terms, Molla eCommerce and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>
                
                <h3 class="mt-4 mb-2">3. Restrictions</h3>
                <p>You are specifically restricted from all of the following: publishing any Website material in any other media, selling, sublicensing and/or otherwise commercializing any Website material.</p>
                
                <h3 class="mt-4 mb-2">4. Limitation of liability</h3>
                <p>In no event shall Molla eCommerce, nor any of its officers, directors and employees, be held liable for anything arising out of or in any way connected with your use of this Website.</p>
            </div>
        </div>';

        Page::updateOrCreate(['slug' => 'terms-condition'], [
            'title' => 'Terms & Conditions',
            'description' => trim($termsDescription),
            'meta_title' => 'Terms & Conditions - Molla eCommerce',
            'meta_description' => 'Terms and conditions of using Molla eCommerce website.',
            'meta_keywords' => 'terms, conditions, agreement, legal',
        ]);

        // 5. Seed Privacy Policy
        $privacyDescription = '
        <div class="row mt-4">
            <div class="col-12">
                <h3 class="mb-2">1. Information We Collect</h3>
                <p>We collect information you provide directly to us, such as when you create or modify your account, purchase products, or contact customer support.</p>
                
                <h3 class="mt-4 mb-2">2. How We Use Information</h3>
                <p>We may use the information we collect to: provide, maintain, and improve our services, process transactions, and send related information.</p>
                
                <h3 class="mt-4 mb-2">3. Sharing of Information</h3>
                <p>We do not share your personal information with third parties except as described in this privacy policy or with your consent.</p>
                
                <h3 class="mt-4 mb-2">4. Security</h3>
                <p>We take reasonable measures to help protect information about you from loss, theft, misuse, and unauthorized access.</p>
            </div>
        </div>';

        Page::updateOrCreate(['slug' => 'privacy-policy'], [
            'title' => 'Privacy Policy',
            'description' => trim($privacyDescription),
            'meta_title' => 'Privacy Policy - Molla eCommerce',
            'meta_description' => 'Privacy policy details for using Molla eCommerce website.',
            'meta_keywords' => 'privacy, policy, data protection, cookies',
        ]);

        // 6. Seed Contact Us page SEO config
        Page::updateOrCreate(['slug' => 'contact'], [
            'title' => 'Contact Us',
            'description' => 'Get in touch with our team for any support questions.',
            'meta_title' => 'Contact Us - Molla eCommerce',
            'meta_description' => 'Get in touch with Molla eCommerce customer support.',
            'meta_keywords' => 'contact, phone, email, support, address',
        ]);
    }
}
