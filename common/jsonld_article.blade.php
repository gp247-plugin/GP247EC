{{--
    JSON-LD — Article schema.

    Delegates to gp247/front's own partial so this template always gets the current
    Article fields (publisher, author, empty-description handling — modification
    20261117T055341) and keeps working on an older front: copying the builder call
    here would pass named arguments an older SeoMeta does not have.

    Expects $jsonldArticle (array) pushed by the content screen:
      headline, url, imageUrl?, datePublished?, dateModified?, description?, author?
--}}
@include('GP247TemplatePath::GP247Front.common.jsonld_article')
