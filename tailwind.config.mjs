/** @type {import('tailwindcss').Config} */
export default {
  content: ['./src/**/*.{astro,html,js,jsx,md,mdx,svelte,ts,tsx,vue}'],
  theme: {
    extend: {
      colors: {
        acid:   '#CCFF00',
        'acid-600': '#A6D400',
        ink:    '#0B0B0B',
        paper:  '#FFFFFF',
        mist:   '#F3F5EE',
        stone:  '#6B7280',
        line:   '#E5E7EB',
      },
      fontFamily: {
        serif: ['Newsreader', 'Source Serif 4', 'Georgia', 'serif'],
        sans:  ['Inter', 'system-ui', 'sans-serif'],
      },
      fontSize: {
        'display-xl': ['80px', { lineHeight: '1.05' }],
        'display-lg': ['64px', { lineHeight: '1.05' }],
        'h2':         ['48px', { lineHeight: '1.1'  }],
        'h2-sm':      ['36px', { lineHeight: '1.1'  }],
        'stat':       ['72px', { lineHeight: '1'    }],
        'h3':         ['22px', { lineHeight: '1.3'  }],
        'body':       ['17px', { lineHeight: '1.6'  }],
        'label':      ['13px', { lineHeight: '1', letterSpacing: '0.12em' }],
        'meta':       ['14px', { lineHeight: '1.4'  }],
      },
      maxWidth: {
        content: '1280px',
      },
      spacing: {
        section: '112px',
        'section-sm': '64px',
      },
    },
  },
  plugins: [],
};
