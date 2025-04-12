import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default {
    server: {
      host: '0.0.0.0', // Allow external access
      port: 5173, // Ensure this port matches the one in your container
    },
  };
  
