import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import Application from '@/components/Application.js';
import SearchModal from '@/components/SearchModal.js';

Alpine.plugin(focus);
Alpine.store('theme', 'system');
Alpine.data('application', Application);
Alpine.data('searchModal', SearchModal);
Alpine.start();
