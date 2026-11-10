/* Block list used by the legal pages (see components/LegalBlocks.astro).
   h2 | h3 | p: plain text. html: a paragraph that contains links. ul: list items. */
export type Block =
  | { t: 'h2' | 'h3' | 'p' | 'html'; x: string }
  | { t: 'ul'; x: string[] };
