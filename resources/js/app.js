import Alpine from 'alpinejs';
import Application from '@/components/Application.js';

Alpine.store('theme', 'system');
Alpine.data('application', Application);
Alpine.start();
