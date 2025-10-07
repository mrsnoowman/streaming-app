# CSS Variables System - Toll Road Monitoring Dashboard

## Overview

Sistem CSS variables yang fleksibel untuk mengubah warna dan styling dashboard CCTV/VMS monitoring dengan mudah.

## File Structure

```
public/css/
├── variables.css    # Main CSS variables file
└── README.md       # Documentation
```

## Color System

### Primary Colors (Based on BOCIMI Logo)

```css
--primary-green: #10b981; /* Main green from logo */
--primary-dark-green: #059669; /* Darker green variant */
--primary-light-green: #34d399; /* Lighter green variant */
--primary-accent: #22c55e; /* Light green accent color */
```

### Secondary Colors

```css
--secondary-green: #10b981; /* VMS theme green */
--secondary-dark-green: #059669; /* Darker green */
--secondary-light-green: #34d399; /* Lighter green */
```

### Light Mode Colors (Default)

```css
--bg-primary: #ffffff; /* White background */
--bg-secondary: #f8fafc; /* Light gray background */
--bg-tertiary: #f1f5f9; /* Medium gray background */
--bg-card: rgba(255, 255, 255, 0.8); /* Card background */
--bg-card-hover: rgba(255, 255, 255, 0.9); /* Card hover */

--text-primary: #0f172a; /* Dark text */
--text-secondary: #334155; /* Medium dark text */
--text-muted: #64748b; /* Muted text */
--text-disabled: #94a3b8; /* Disabled text */
```

### Dark Mode Colors

```css
.dark-mode {
    --bg-primary: #000000; /* Pure black background */
    --bg-secondary: #111111; /* Dark gray background */
    --bg-tertiary: #1a1a1a; /* Medium gray background */
    --bg-card: rgba(255, 255, 255, 0.1); /* Card background */
    --bg-card-hover: rgba(255, 255, 255, 0.05); /* Card hover */

    --text-primary: #ffffff; /* White text */
    --text-secondary: #e2e8f0; /* Light gray text */
    --text-muted: #94a3b8; /* Muted text */
    --text-disabled: #64748b; /* Disabled text */
}
```

## Theme System

### CCTV Theme (Green)

```css
body.theme-cctv {
    --primary-color: var(--primary-green);
    --primary-dark: var(--primary-dark-green);
    --primary-light: var(--primary-light-green);
    --accent-color: var(--primary-accent);
    --status-color: var(--status-online);
    --status-bg: var(--status-online-bg);
    --shadow-color: var(--shadow-green);
}
```

### VMS Theme (Green)

```css
body.theme-vms {
    --primary-color: var(--secondary-green);
    --primary-dark: var(--secondary-dark-green);
    --primary-light: var(--secondary-light-green);
    --accent-color: var(--secondary-light-green);
    --status-color: var(--status-online);
    --status-bg: var(--status-online-bg);
    --shadow-color: var(--shadow-green);
}
```

## Dark/Light Mode System

### Default Theme

-   **Default**: Light Mode
-   **Toggle Button**: Fixed position (top-right corner)
-   **Persistence**: Theme preference saved in localStorage
-   **Icons**: Sun icon for light mode, Moon icon for dark mode

### Theme Toggle Button

```html
<div class="theme-toggle" id="themeToggle" title="Toggle Dark/Light Mode">
    <i class="fas fa-sun"></i>
</div>
```

### JavaScript Functionality

```javascript
// Theme toggle functionality
function initThemeToggle() {
    const themeToggle = document.getElementById("themeToggle");
    const body = document.body;
    const icon = themeToggle.querySelector("i");

    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem("theme") || "light";
    applyTheme(savedTheme);

    themeToggle.addEventListener("click", function () {
        const currentTheme = body.classList.contains("dark-mode")
            ? "dark"
            : "light";
        const newTheme = currentTheme === "dark" ? "light" : "dark";

        applyTheme(newTheme);
        localStorage.setItem("theme", newTheme);
    });

    function applyTheme(theme) {
        if (theme === "dark") {
            body.classList.add("dark-mode");
            icon.className = "fas fa-moon";
            themeToggle.title = "Switch to Light Mode";
        } else {
            body.classList.remove("dark-mode");
            icon.className = "fas fa-sun";
            themeToggle.title = "Switch to Dark Mode";
        }
    }
}
```

## How to Change Colors

### Method 1: Edit CSS Variables File

1. Open `public/css/variables.css`
2. Modify the color values in `:root` section for light mode
3. Modify the color values in `.dark-mode` section for dark mode
4. Save the file
5. Refresh the browser

Example:

```css
:root {
    /* Light mode colors */
    --primary-green: #16a34a;
    --primary-dark-green: #15803d;
    --primary-light-green: #4ade80;
    --primary-accent: #22c55e;
}

.dark-mode {
    /* Dark mode colors */
    --bg-primary: #0a0a0a;
    --bg-secondary: #1a1a1a;
    --text-primary: #f0f0f0;
}
```

### Method 2: Override in HTML

Add custom CSS in the `<head>` section:

```html
<style>
    :root {
        --primary-green: #your-color;
        --primary-dark-green: #your-darker-color;
    }
</style>
```

### Method 3: Create New Theme

Add a new theme class in `variables.css`:

```css
.theme-custom {
    --primary-color: #your-primary;
    --primary-dark: #your-dark;
    --primary-light: #your-light;
    --accent-color: #your-accent;
    --status-color: var(--status-online);
    --status-bg: var(--status-online-bg);
    --shadow-color: rgba(your-color, 0.3);
}
```

Then apply to body:

```html
<body class="theme-custom"></body>
```

## Spacing System

```css
--spacing-xs: 4px;
--spacing-sm: 8px;
--spacing-md: 12px;
--spacing-lg: 16px;
--spacing-xl: 20px;
--spacing-2xl: 24px;
--spacing-3xl: 32px;
--spacing-4xl: 40px;
```

## Border Radius System

```css
--radius-sm: 8px;
--radius-md: 12px;
--radius-lg: 16px;
--radius-xl: 20px;
--radius-2xl: 24px;
```

## Typography System

```css
--font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
--font-size-xs: 10px;
--font-size-sm: 12px;
--font-size-base: 14px;
--font-size-lg: 16px;
--font-size-xl: 18px;
--font-size-2xl: 20px;
--font-size-3xl: 24px;
--font-size-4xl: 28px;
--font-size-5xl: 32px;
```

## Usage Examples

### Change Logo Colors

```css
.logo {
    background: var(--gradient-primary); /* Uses primary colors */
    box-shadow: 0 8px 20px var(--shadow-color); /* Uses theme shadow */
}
```

### Change Button Colors

```css
.nav-btn:hover {
    background: var(--gradient-primary);
    box-shadow: 0 8px 20px var(--shadow-color);
}
```

### Change Card Colors

```css
.video-card {
    background: var(--gradient-card);
    border: 1px solid var(--border-primary);
}
```

## Best Practices

1. **Always use CSS variables** instead of hardcoded colors
2. **Test color changes** on both CCTV and VMS pages
3. **Ensure contrast ratios** meet accessibility standards
4. **Use semantic naming** for color variables
5. **Group related colors** together in the variables file

## Logo Integration

The system now supports custom logos:

```html
<div class="logo">
    <img
        src="https://10.61.0.15:5005/images/Bismillah-Bocimi.png"
        alt="BOCIMI Logo"
    />
</div>
```

Logo styling:

```css
.logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: var(--radius-lg);
}
```

## Performance Notes

-   CSS variables are cached by the browser
-   Changes to `variables.css` require a page refresh
-   Use CSS variables for consistent theming across components
-   Avoid overriding variables in individual components

## Support

For questions or issues with the CSS system, refer to:

-   CSS Variables documentation
-   Browser compatibility charts
-   Design system guidelines
