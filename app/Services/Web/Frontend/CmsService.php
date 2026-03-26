<?php

namespace App\Services\Web\Frontend;

use App\Enums\Page;
use App\Models\CMS;
use Exception;

class CmsService
{
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function get()
    {
        try {
            // Logic to fetch all resources
            // Define sections and their constraints
            $sections = [
                'banner' => ['type' => 'take', 'limit' => 10],
                'service_container' => ['type' => 'first'],
                'process_container' => ['type' => 'first'],
                'plat_form_work_container' => ['type' => 'first'],
                'provider_work_container' => ['type' => 'first'],
                'review_user_container' => ['type' => 'first'],
                'review_provider_container' => ['type' => 'first'],
                'faq_container' => ['type' => 'first'],
                'service_container_content' => ['type' => 'take', 'limit' => 5],
                'process_container_content' => ['type' => 'take', 'limit' => 3],
                'plat_form_work_container_content' => ['type' => 'take', 'limit' => 3],
                'faq_container_content' => ['type' => 'take', 'limit' => 3],
            ];

            // Fetch all required sections in a single query
            $cmsData = CMS::where('page', 'home_page')
                ->where('status', 'active')
                ->whereIn('section', array_keys($sections))
                ->orderBy('created_at', 'desc')
                ->get();

            $advertisementSection = CMS::where('page', 'home_page')
                ->where('section', 'advertisement_container')
                ->where('status', 'active')->first();

            // Process the sections into the desired format
            $cms = [
                'advertisementSection' => $advertisementSection
            ];
            foreach ($sections as $key => $config) {
                $filteredData = $cmsData->where('section', $key);
                $cms[$key] = $config['type'] === 'first'
                    ? $filteredData->first()
                    : $filteredData->take($config['limit']);
            }

            return $cms;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        try {
            // Logic for create form
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Store a new resource.
     *
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        try {
            // Logic to store a new resource
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Display a specific resource.
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        try {
            // Logic to show a specific resource
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Show the form for editing a resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        try {
            // Logic for edit form
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update a specific resource.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, array $data)
    {
        try {
            // Logic to update a specific resource
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Delete a specific resource.
     *
     * @param int $id
     * @return bool
     */
    public function destroy(int $id)
    {
        try {
            // Logic to delete a specific resource
        } catch (Exception $e) {
            throw $e;
        }
    }
}
