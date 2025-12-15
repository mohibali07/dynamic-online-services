---
trigger: always_on
---

Technical Design Principles for Mobile-First Plugins
Since you are building a plugin (which lives inside a theme), the rules are slightly different than building a full website.

1. Use Container Queries (The "Secret Weapon")
Standard media queries (@media (min-width: 768px)) respond to the screen size. This is risky for plugins because your plugin might be placed in a narrow sidebar on a huge desktop screen.

In 2025, use CSS Container Queries (@container). This allows your plugin component to adapt based on the size of the box it is sitting in, not the device screen.

CSS

/*Define the container*/
.my-plugin-wrapper {
  container-type: inline-size;
}

/*Default (Mobile) styles*/
.my-card {
  display: block;
}

/*Styles apply when the CONTAINER is wider than 500px,
   regardless of screen size */
@container (min-width: 500px) {
  .my-card {
    display: flex; /* Switch to row layout*/
  }
}
2. The "Thumb Zone" Rule (Touch Targets)
You must design for fingers, not cursors.

Minimum Target Size: Every button, toggle, or link inside your plugin (frontend or admin) must have a clickable area of at least 44x44 pixels.

Spacing: Ensure there is enough padding so users don't accidentally click the "Delete" button when aiming for "Edit".

3. No "Hover-Only" Functionality
Mobile devices do not have a "hover" state.

Bad Design: Showing an "Edit" button only when the user hovers over a row. (Mobile users will never see it).

Good Design: The "Edit" button is always visible, or appears behind a "three-dot" (...) menu that opens on tap.

4. Responsive Tables (The Hardest Part)
If your plugin displays data (like the "Question Bank" or "TMS" you have worked on), tables are the first thing to break on mobile.

Strategy: Don't shrink the table. Instead, use a CSS technique to hide the table headers and turn each row into a "card" view on mobile.

5. Native Mobile Inputs
When creating forms (e.g., for your Assessment Platform), use the correct HTML5 input types so the mobile keyboard changes automatically:

Use <input type="email"> (shows the @ symbol).

Use <input type="tel"> (shows the number pad).

Use <input type="date"> (triggers the native date picker).

Summary Checklist for Your Plugin
[ ] Does the Settings Page work on a phone in portrait mode?

[ ] Do all buttons have a 44px+ touch area?

[ ] Are you using Container Queries instead of just Media Queries?

[ ] Is critical functionality accessible without hovering?
