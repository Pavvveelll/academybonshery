module.exports = {
  content: ['./index.html', './src/**/*.{ts,tsx}'],
  theme: {
    extend: {
      fontFamily: {
        display: ['"Manrope"', 'sans-serif'],
        body: ['"IBM Plex Sans"', 'sans-serif']
      },
      colors: {
        base: '#f5f3ef',
        ink: '#1d1b19',
        accent: '#d56a3d',
        sea: '#2c6e68'
      }
    }
  },
  plugins: []
};
