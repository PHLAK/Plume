import Alpine from 'alpinejs';
import Application from '@/components/Application.js';

Alpine.store('theme', 'light');
Alpine.data('application', Application);
Alpine.start();
