import './bootstrap';

// Alpine.js dengan plugins
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';
import Persist from '@alpinejs/persist';

Alpine.plugin(Collapse);
Alpine.plugin(Persist);

window.Alpine = Alpine;
Alpine.start();
