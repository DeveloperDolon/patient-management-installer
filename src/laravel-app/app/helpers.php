<?php

if (!function_exists('getMenuStyles')) {
    function getMenuStyles($routeName, $currentRouteName = null)
    {
        try {
            
            $isActive = $currentRouteName === $routeName;
            
            return [
                'mt-4 mb-4 cursor-pointer py-2 pl-6 md:text-base font-medium bg-indigo-100 rounded-r-3xl hover:bg-indigo-200 text-sm leading-3 tracking-normal focus:outline-none',
                'text-indigo-700 hover:text-indigo-700 focus:text-indigo-700 bg-indigo-200 rounded-r-3xl' => $isActive,
                'text-gray-600 hover:text-indigo-700 focus:text-indigo-700' => !$isActive,
            ];
        } catch (\Exception $e) {
            return [
                'mt-4 mb-4 cursor-pointer py-2 pl-6 md:text-base font-medium rounded-r-3xl bg-indigo-100 hover:bg-indigo-200 text-sm leading-3 tracking-normal focus:outline-none transition-all duration-200 ease-in-out',
                'text-gray-600 hover:text-indigo-700 focus:text-indigo-700'
            ];
        }
    }
}
